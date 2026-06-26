<?php

namespace CharlesStOlive\FilamentStaticPages\Blocks\SubBlocks\Contracts;

interface SubBlockContract
{
    /**
     * Identifiant unique du sous-bloc (utilisé comme clé dans le Builder Filament).
     */
    public static function type(): string;

    /**
     * Label affiché dans l'interface Filament.
     */
    public static function label(): string;

    /**
     * Champs Filament du sous-bloc.
     *
     * @return array<\Filament\Schemas\Components\Component|\Filament\Forms\Components\Field>
     */
    public static function schema(): array;

    /**
     * Nom de la vue Blade utilisée pour le rendu front.
     * Exemple : 'filament-static-pages::components.blocks.sub.texte-photo'
     */
    public static function view(): string;
}
