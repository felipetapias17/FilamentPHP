<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Schemas\Components\Utilities\Set;
use App\Models\State;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\FormsComponent;
use Illuminate\Support\Collection;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Symfony\Component\Console\Input\Input;
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

                Section::make('Address Info')
                ->inlineLabel()
                ->schema([
                    Select::make('country_id')
                    ->relationship('country', titleAttribute:'name')
                    ->searchable()
                    ->preload()
                    ->live()
                    ->afterStateUpdated(function(Set $set) { $set('state_id', null);
                    $set('city_id', null);
                    })
                    ->required(),
                    Select::make('state_id')
                    ->options(fn(callable $get)=> 
                    \App\Models\State::where('country_id', $get('country_id'))
                    ->pluck('name', 'id'))
                    ->searchable()
                    ->preload()
                    ->live()
                    ->afterStateUpdated(function(Set $set) { $set('city_id', null); })
                    ->required(),    
                    Select::make('city_id')
                    ->options(fn(callable $get)=> 
                    \App\Models\City::where('state_id', $get('state_id'))
                    ->pluck('name', 'id'))
                    ->searchable()
                    ->preload()
                    ->live()
                    ->required(),

                    TextInput::make('address')
                    ->required(),
                    TextInput::make('postal_code')
                    ->required(),
                ])
                ->columns(3)
                ->columnSpanFull(),
            ]);
    }
}
