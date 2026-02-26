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
    # Configura el esquema del formulario para la entidad User, definiendo las secciones y campos necesarios para la creación y edición de usuarios en el panel de administración de Filament
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

            # Sección para la información personal del usuario, con campos para nombre, correo electrónico, fecha de verificación y contraseña
            Section::make('Personal Info')
            ->inlineLabel()
            ->schema([
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                DateTimePicker::make('email_verified_at')->hiddenOn('edit'),
                TextInput::make('password')
                    ->password()
                    ->hiddenOn('edit') #Oculta el campo de contraseña al editar un usuario existente
                    ->required(),
                ])
                ->columns(2)
                ->columnSpanFull(),


                # Sección para la información de dirección, con campos dependientes para país, estado y ciudad 
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

                    #Campo de selección para el estado, con opciones dependientes del país seleccionado
                    Select::make('state_id')
                    ->options(fn(callable $get)=> 
                    \App\Models\State::where('country_id', $get('country_id'))
                    ->pluck('name', 'id'))
                    ->searchable()
                    ->preload()
                    ->live()
                    ->afterStateUpdated(function(Set $set) { $set('city_id', null); })
                    ->required(),

                    #Campo de selección para la ciudad, con opciones dependientes del estado seleccionado    
                    Select::make('city_id')
                    ->options(fn(callable $get)=> 
                    \App\Models\City::where('state_id', $get('state_id'))
                    ->pluck('name', 'id'))
                    ->searchable()
                    ->preload()
                    ->live()
                    ->required(),

                    TextInput::make('address') #Campo de texto para la dirección
                    ->required(),
                    TextInput::make('postal_code') #Campo de texto para el código postal
                    ->required(),
                ])
                ->columns(3) #Organiza los campos en 3 columnas
                ->columnSpanFull(), #Hace que la sección ocupe todo el ancho del formulario
            ]);
    }
}
