<?php

namespace App\Actions;

use Closure;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class UpdateStatusAction extends Action
{
    protected string $statusField = 'status';
    protected string $enumClass;

    public static function make(?string $name = 'update_status'): static
    {
        return parent::make($name)
            ->label('Update Status')
            ->icon('fas-wrench')
            ->modalWidth('sm')
            ->color("warning")
            ->modalHeading('Update Status')
            ->modalDescription('Change the status of this record.')
            ->modal();
    }

    public function statusField(string $field): static
    {
        $this->statusField = $field;

        return $this;
    }

    public function enumClass(string $class): static
    {
        $this->enumClass = $class;

        $this->form(function (Model $record) use ($class) {
            return [
                Select::make($this->statusField)
                    ->label('Status')
                    ->required()
                    ->options($class)
                    ->default($record->{$this->statusField})
                    ->native(false),
            ];
        });

        return $this;
    }

    public function setUp(): void
    {
        parent::setUp();

        $this->action(function (Model $record, array $data) {
            $record->update([$this->statusField => $data[$this->statusField]]);

            if(Schema::hasColumn($record->getTable(), 'status_at')){
                $record->update([
                    "status_at" => now(),
                ]);
            }

            Notification::make()
                ->title(__('Status updated successfully'))
                ->success()
                ->send();
        });
    }
}
