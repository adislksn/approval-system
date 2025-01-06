<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserApprovalResource\Pages;
use App\Filament\Resources\UserApprovalResource\RelationManagers;
use App\Models\UserApproval;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserApprovalResource extends Resource
{
    protected static ?string $model = UserApproval::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                    ->preload()
                    ->searchable()
                    ->relationship('user', 'name'),
                FileUpload::make('ttd_path')
                    ->image()
                    ->required()
                    ->imageEditor(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->paginated(false)
            ->columns([
                TextColumn::make('user.name')
                    ->label('User')
                    ->sortable(),
                ImageColumn::make('ttd_path')
                    ->stacked()
                    ->circular()
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                //
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
            'index' => Pages\ListUserApprovals::route('/'),
            // 'create' => Pages\CreateUserApproval::route('/create'),
            'edit' => Pages\EditUserApproval::route('/{record}/edit'),
        ];
    }
}
