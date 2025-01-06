<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReportsResource\Pages;
use App\Filament\Resources\ReportsResource\RelationManagers;
use App\Jobs\MailerJob;
use App\Models\Report;
use App\Models\User;
use App\Models\UserApproval;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class ReportsResource extends Resource
{
    protected static ?string $model = Report::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('user_id')
                    ->label('User ID')
                    ->hidden()
                    ->default(Auth::id()),
                Group::make([
                    TextInput::make('name')
                        ->label('User Name')
                        ->disabled()
                        ->default(Auth::user()->name)
                        ->dehydrateStateUsing(null),
                    TextInput::make('email')
                        ->label('Email')
                        ->disabled()
                        ->default(Auth::user()->email)
                        ->dehydrateStateUsing(null),
                ])
                ->columnSpanFull()
                ->relationship('user'),
                Fieldset::make('')
                    ->schema([
                        TextInput::make('nopol')
                            ->label('Nomor Polisi')
                            ->required(),
                        TextInput::make('tahun')
                            ->label('Tahun')
                            ->numeric()
                            ->minValue(1930)
                            ->maxValue(date('Y'))
                            ->default(date('Y'))
                            ->required(),
                        Select::make('merk')
                            ->label('Merk')
                            ->options([
                                'Izuzu' => 'Izuzu',
                                'Daihatsu' => 'Daihatsu',
                                'TATA' => 'TATA',
                                'Mitsubishi' => 'Mitsubishi',
                                'Hino' => 'Hino',
                                'none' => 'None',
                            ])
                            ->default('none')
                            ->required(),
                        Select::make('shipping_type')
                            ->label('Shipping Type')
                            ->options([
                                '4A' => '4A',
                                '4B' => '4B',
                                '4G' => '4G',
                                '4R' => '4R',
                                'GD' => 'GD',
                                '6R' => '6R',
                                '6H' => '6H',
                                'WR' => 'WR',
                                'none' => 'None',
                            ])
                            ->default('none')
                            ->required(),
                        TextInput::make('kilometer')
                            ->label('Kilometer')
                            ->numeric()
                            ->required(),
                        Textarea::make('service_location')
                            ->label('Lokasi Service')
                            ->required(),
                    ]),
                Fieldset::make('')
                    ->schema([
                        Repeater::make('description_service')
                            ->label('Deskripsi Service')
                            ->addActionLabel('Tambah Deskripsi Service')
                            ->schema([
                                Textarea::make('description')
                                    ->label('Deskripsi')
                                    ->required(),
                            ])
                            ->required(),
                        FileUpload::make('images')
                            ->label('Dokumentasi Kerusakan')
                            ->image()
                            ->multiple()
                            ->imageEditor(),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(self::getFilteredQuery())
            ->columns([
                TextColumn::make('nopol')
                    ->label('Nomor Polisi')
                    ->searchable()
                    ->sortable(),
                SelectColumn::make('status')
                    ->label('Status')
                    ->options(function ($record) {
                        $user = Auth::user();
                        $data = UserApproval::with('user')->get();

                        $statusMap = [
                            'pending' => [
                                'roles' => ['approver', 'super_admin'],
                                'id' => 1,
                                'transitions' => [
                                    'pending' => 'Pending',
                                    'accepted 1' => 'Accepted by ' . $data[0]->user->name,
                                    'rejected' => 'Rejected',
                                ],
                            ],
                            'accepted 1' => [
                                'roles' => ['approver', 'super_admin'],
                                'id' => 2,
                                'transitions' => [
                                    'accepted 2' => 'Accepted by ' . $data[1]->user->name,
                                    'accepted 1' => 'Accepted by ' . $data[0]->user->name,
                                    'rejected' => 'Rejected',
                                ],
                            ],
                            'accepted 2' => [
                                'roles' => ['approver', 'super_admin'],
                                'id' => 3,
                                'transitions' => [
                                    'accepted 2' => 'Accepted by ' . $data[1]->user->name,
                                    'done' => 'Accepted',
                                    'rejected' => 'Rejected',
                                ],
                            ],
                        ];

                        $recordStatus = $record->status;

                        foreach ($statusMap as $key => $settings) {
                            if ((($user->hasRole($settings['roles'][0]) && $user->userApproval?->id == $settings['id']) || $user->hasRole($settings['roles'][1])) && $recordStatus === $key) {
                                return $settings['transitions'];
                            }
                        }

                        // Default status handling
                        switch ($recordStatus) {
                            case 'accepted 1':
                                return [
                                    'accepted 1' => 'Accepted by ' . $data[0]->user->name,
                                ];
                            case 'accepted 2':
                                return [
                                    'accepted 2' => 'Accepted by ' . $data[1]->user->name,
                                ];
                            case 'done':
                                return [
                                    'done' => 'Accepted',
                                ];
                            default:
                                return [
                                    $recordStatus => $recordStatus,
                                ];
                        }
                    })
                    ->disabled(!(Auth::user()->hasRole('super_admin') || Auth::user()->hasRole('approver')))
                    ->afterStateUpdated(function ($record, $state) {
                        if ($state === 'done') {
                            $user = User::find($record->user_id);
                            // MailHelpers::sendPDF($record->images, $user, $record->toArray());
                            MailerJob::dispatch($record->images, $user, $record->toArray());
                        }
                    })
                    ->selectablePlaceholder(false)
                    ->searchable()
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('User')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->searchable()
                    ->sortable(),
                ImageColumn::make('images')
                    ->stacked()
                    ->circular()
            ])
            ->defaultSort('created_at', 'desc')
            ->filters(
                [
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'accepted 1' => 'Accepted by ' . UserApproval::find(1)->user->name,
                        'accepted 2' => 'Accepted by ' . UserApproval::find(2)->user->name,
                        'done' => 'Accepted',
                        'rejected' => 'Rejected',
                    ]),
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from'),
                        DatePicker::make('created_until'),
                    ])
                    ->columns(2)
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
            ], layout: FiltersLayout::AboveContent)
            ->actions([
                Tables\Actions\ViewAction::make()
                ->hiddenLabel(),
                Tables\Actions\EditAction::make()
                ->hidden(fn ($record) => !(in_array($record->status, ['pending', 'rejected'])))
                ->hiddenLabel(),
            ], position: ActionsPosition::BeforeColumns)
            ->bulkActions([
                BulkAction::make('delete')
                    ->requiresConfirmation()
                    ->action(fn (Collection $records) => $records->each->delete())
            ]);
    }

    protected static function getFilteredQuery(): Builder
    {
        return Report::query()
            ->when(! (Auth::user()->hasRole('super_admin') || Auth::user()->hasRole('approver')), function ($query) {
                $query->where('user_id', Auth::id());
            });
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReports::route('/'),
            'create' => Pages\CreateReports::route('/create'),
            'edit' => Pages\EditReports::route('/{record}/edit'),
        ];
    }
}
