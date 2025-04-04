<?php

namespace App\Actions;

use Filament\Forms\Components\Select;
use Filament\Tables;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Schema;

class UpdateStatusBulkAction extends Tables\Actions\BulkAction
{
    protected string $statusField = 'status';
    protected string $enumClass;

    public static function make(?string $name = "update_status_bulk"): static
    {
        return parent::make($name)
            ->label('Update Status')
            ->icon('fas-wrench')
            ->modalWidth('sm')
            ->color("warning")
            ->modalHeading('Update Status')
            ->modalDescription('Change the status of this records.')
            ->requiresConfirmation()
            ->deselectRecordsAfterCompletion();
    }

    public function statusField(string $field): static
    {
        $this->statusField = $field;

        return $this;
    }

    public function enumClass(string $class): static
    {
        $this->enumClass = $class;

        $this->form([
            Select::make($this->statusField)
                ->label('Status')
                ->required()
                ->options($class)
                ->native(false),
        ]);

        return $this;
    }

    public function setUp(): void
    {
        parent::setUp();

        $this->action(function (array $data, Collection $records, Tables\Actions\BulkAction $action) {
            $status = $data[$this->statusField];

            $records->each(function ($record) use ($status) {
                $record->update([$this->statusField => $status]);

                if(Schema::hasColumn($record->getTable(), 'status_at')){
                    $record->update([
                        "status_at" => now(),
                    ]);
                }
            });

            $action->success();
        });
    }
}
