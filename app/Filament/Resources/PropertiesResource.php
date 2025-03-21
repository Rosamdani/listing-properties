<?php

namespace App\Filament\Resources;

use App\Enum\PropertyStatus;
use App\Enum\PropertyType;
use App\Filament\Resources\PropertiesResource\Pages;
use App\Filament\Resources\PropertiesResource\RelationManagers;
use App\Models\Properties;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use RalphJSmit\Filament\SEO\SEO;

class PropertiesResource extends Resource
{
    protected static ?string $model = Properties::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('PropertiesTabs')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Informasi Properti')
                            ->icon('heroicon-o-home')
                            ->columns(2)
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\Select::make('type')
                                    ->options(PropertyType::class)
                                    ->required(),
                                Forms\Components\TextInput::make('location')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('latitude')
                                    ->required(),
                                Forms\Components\TextInput::make('longitude')
                                    ->required(),
                                Forms\Components\TextInput::make('bedrooms')
                                    ->required()
                                    ->integer(),
                                Forms\Components\TextInput::make('bathrooms')
                                    ->required()
                                    ->integer(),
                                Forms\Components\TextInput::make('garages')
                                    ->required()
                                    ->integer(),
                                Forms\Components\TextInput::make('area')
                                    ->required()
                                    ->integer(),
                                Forms\Components\Toggle::make('furnished')
                                    ->required(),
                                Forms\Components\DatePicker::make('available_from')
                                    ->required(),
                                Forms\Components\Select::make('status')
                                    ->options(PropertyStatus::class)
                                    ->required(),
                                Forms\Components\TextInput::make('price')
                                    ->required()
                                    ->numeric(),
                            ]),
                        Forms\Components\Tabs\Tab::make('Gambar')
                            ->schema([
                                Forms\Components\FileUpload::make('images')
                                    ->multiple()
                                    ->image()
                                    ->imageEditor(),
                            ]),
                        Forms\Components\Tabs\Tab::make('SEO')
                            ->schema([
                                SEO::make(),
                            ]),
                    ])->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('type'),
                Tables\Columns\TextColumn::make('location'),
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('price'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(PropertyStatus::class),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListProperties::route('/'),
            'create' => Pages\CreateProperties::route('/create'),
            'edit' => Pages\EditProperties::route('/{record}/edit'),
        ];
    }
}
