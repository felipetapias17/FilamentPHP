<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table #Configura la tabla para la entidad User, definiendo las columnas a mostrar, los filtros disponibles y las acciones que se pueden realizar sobre los registros de usuarios en el panel de administración de Filament
            ->columns([
                TextColumn::make('name')
                    ->searchable(), #Columna para mostrar el nombre del usuario, con capacidad de búsqueda
                TextColumn::make('email')
                    ->label('Email address')
                    ->searchable(),
                TextColumn::make('address') #Columna para mostrar la dirección del usuario
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false), #Permite ocultar o mostrar la columna de dirección en la tabla
                TextColumn::make('postal_code') #Columna para mostrar el código postal del usuario
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('email_verified_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),   
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
