<?php

namespace CharlesStOlive\FilamentStaticPages\RichEditor\Plugins;

use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\RichEditor\EditorCommand;
use Filament\Forms\Components\RichEditor\Plugins\Contracts\RichContentPlugin;
use Filament\Forms\Components\RichEditor\RichEditorTool;
use Filament\Support\Enums\Width;

class OrderedListPlugin implements RichContentPlugin
{
    public static function make(): static
    {
        return app(static::class);
    }

    public function getTipTapPhpExtensions(): array
    {
        return [];
    }

    public function getTipTapJsExtensions(): array
    {
        return [];
    }

    public function getEditorTools(): array
    {
        return [
            RichEditorTool::make('customOrderedList')
                ->action(arguments: '{
                    selectedText: $getEditor().state.doc.textBetween($getEditor().state.selection.from, $getEditor().state.selection.to),
                    hasSelection: !$getEditor().state.selection.empty
                }')
                ->icon('heroicon-o-numbered-list')
                ->label('Liste numérotée (numéro de départ)'),
        ];
    }

    public function getEditorActions(): array
    {
        return [
            Action::make('customOrderedList')
                ->label('Liste numérotée personnalisée')
                ->modalHeading('Numéro de départ de la liste')
                ->modalWidth(Width::Medium)
                ->fillForm(function (array $arguments): array {
                    return [
                        'start' => 1,
                    ];
                })
                ->schema([
                    TextInput::make('start')
                        ->label('Numéro de départ')
                        ->numeric()
                        ->default(1)
                        ->required()
                        ->minValue(1),
                ])
                ->action(function (array $arguments, array $data, RichEditor $component): void {
                    $startNumber = $data['start'];

                    // D'abord créer ou convertir en liste ordonnée
                    $component->runCommands([
                        EditorCommand::make('toggleOrderedList'),
                    ], editorSelection: $arguments['editorSelection']);

                    // Ensuite mettre à jour l'attribut start si différent de 1
                    if ($startNumber != 1) {
                        $component->runCommands([
                            EditorCommand::make('updateAttributes', arguments: ['orderedList', ['start' => $startNumber]]),
                        ], editorSelection: $arguments['editorSelection']);
                    }
                }),
        ];
    }
}
