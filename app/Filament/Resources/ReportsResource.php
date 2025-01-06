<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReportsResource\Pages;
use App\Filament\Resources\ReportsResource\RelationManagers;
use App\Helpers\MailHelpers;
use App\Models\Report;
use App\Models\User;
use App\Models\UserApproval;
use Dompdf\FrameDecorator\Text;
use Filament\Forms;
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
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ReportsResource extends Resource
{
    protected static ?string $model = Report::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
                        TextInput::make('estimation_cost')
                            ->label('Estimasi Biaya')
                            ->mask(RawJs::make('$money($input)'))
                            ->stripCharacters(',')
                            ->prefix('Rp. ')
                            ->numeric()
                            ->required(),
                        Textarea::make('service_location')
                            ->label('Lokasi Service')
                            ->columnSpanFull()
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
                        // dd($user->userApproval?->id);
                        if ((($user->hasRole('approver') && $user->userApproval?->id == 1) || $user->hasRole('super_admin')) && ($record->status === 'pending' || $record->status === 'rejected')) {
                            return [
                                'pending' => 'Pending',
                                'accepted 1' => 'Accepted by ' . $data[0]->user->name,
                                'rejected' => 'Rejected',
                            ];
                        } else if ((($user->hasRole('approver') && $user->userApproval?->id == 2) || $user->hasRole('super_admin')) && $record->status == 'accepted 1') {
                            return [
                                'accepted 1' => 'Accepted by ' . $data[0]->user->name,
                                'accepted 2' => 'Accepted by ' . $data[1]->user->name,
                                'rejected' => 'Rejected',
                            ];
                        } else if ((($user->hasRole('approver') && $user->userApproval?->id == 3) || $user->hasRole('super_admin')) && $record->status == 'accepted 2') {
                            return [
                                'accepted 2' => 'Accepted by ' . $data[1]->user->name,
                                'done' => 'Approved',
                                'rejected' => 'Rejected',
                            ];
                        } else {
                            if ($record->status === 'accepted 1') {
                                return [
                                    'accepted 1' => 'Accepted by ' . $data[0]->user->name,
                                ];
                            } else if ($record->status === 'accepted 2') {
                                return [
                                    'accepted 2' => 'Accepted by ' . $data[1]->user->name,
                                ];
                            } else if ($record->status === 'done') {
                                return [
                                    'done' => 'Approved',
                                ];
                            } else {
                                return [
                                    $record->status => $record->status,
                                ];
                            }
                        }
                    })
                    ->disabled(!(Auth::user()->hasRole('super_admin') || Auth::user()->hasRole('approver')))
                    ->afterStateUpdated(function ($record, $state) {
                        if ($state === 'done') {
                            $user = User::find($record->user_id);
                            MailHelpers::sendPDF($record->images, $user, $record->toArray());
                        }
                    })
                    ->selectablePlaceholder(false)
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('User')
                    ->sortable(),
                TextColumn::make('estimation_cost')
                    ->label('Estimasi Biaya')
                    ->money('IDR', divideBy: 1000)
                    ->searchable()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->searchable()
                    ->sortable(),
                ImageColumn::make('images')
                    ->stacked()
                    ->circular()
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                ->hiddenLabel(),
                Tables\Actions\EditAction::make()
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
