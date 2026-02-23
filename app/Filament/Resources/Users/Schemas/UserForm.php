<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

            Section::make('Personal Info')
            ->inlineLabel()
            ->schema([
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                DateTimePicker::make('email_verified_at'),
                TextInput::make('password')
                    ->password()
                    ->required(),
                ])
                ->columns(2)
                ->columnSpanFull(),
            ]);
    }
}
