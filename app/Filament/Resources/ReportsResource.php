<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReportsResource\Pages;
use App\Filament\Resources\ReportsResource\RelationManagers;
use App\Models\Report;
use Dompdf\FrameDecorator\Text;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class ReportsResource extends Resource
{
    protected static ?string $model = Report::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Fieldset::make('')
                    ->schema([
                        TextInput::make('user_id')
                            ->label('User ID')
                            ->hidden()
                            ->default(fn () => Auth::id()),
                        TextInput::make('user_name')
                            ->label('User Name')
                            ->disabled()
                            ->default(fn () => Auth::user()->name),
                        TextInput::make('email')
                            ->label('Email')
                            ->disabled()
                            ->default(fn () => Auth::user()->email),
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
                        TextInput::make('estimation_cost')
                            ->label('Estimasi Biaya')
                            ->mask(RawJs::make('$money($input)'))
                            ->stripCharacters(',')
                            ->prefix('Rp. ')
                            ->numeric()
                            ->required(),
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
            ->columns([
                TextColumn::make('user.name')
                    ->label('User')
                    ->sortable(),
                TextColumn::make('nopol')
                    ->label('Nomor Polisi')
                    ->searchable()
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
                SelectColumn::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'accepted' => 'Accepted',
                        'rejected' => 'Rejected',
                    ])
                    ->afterStateUpdated(function ($record, $state) {
                        // Runs after the state is saved to the database.
                    })
                    ->sortable(),
                ImageColumn::make('images')
                    ->stacked()
                    ->circular()
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ], position: ActionsPosition::BeforeColumns)
            ->bulkActions([
                BulkAction::make('delete')
                    ->requiresConfirmation()
                    ->action(fn (Collection $records) => $records->each->delete())
                ]);
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
