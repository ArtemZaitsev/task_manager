<?php

namespace App\Models\Component;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

/**
 * @property int $id
 * @property string $title
 * @property string $identifier
 * @property int $source_type
 * @property int $version
 * @property int $type
 * @property int $constructor_id
 *
 * @property User $constructor
 *
 * @property int $dd_status
 * @property \DateTime $dd_date_plan
 * @property string $drawing_files
 * @property \DateTime $drawing_date
 * @property int $calc_status
 * @property \DateTime $calc_date_plan
 * @property string $tz_files
 * @property \DateTime $tz_date
 * @property int $constructor_priority
 * @property string $constructor_comment

 * @property int $manufactor_id
 * @property ?User $manufactor
 * @property int $manufactor_status
 * @property \DateTime $manufactor_date_plan
 * @property string $manufactor_sz_files
 * @property \DateTime $manufactor_sz_date
 * @property int $manufactor_sz_quantity
 * @property int $manufactor_priority
 * @property string $manufactor_comment
 *
 * @property int $purchaser_id
 * @property ?User $purchaser
 * @property int $purchase_status
 * @property \DateTime $purchase_date_plan
 * @property $purchase_request_files
 * @property $purchase_request_date
 * @property $purchase_request_quantity
 * @property $purchase_request_priority
 * @property $purchase_comment
 * @property boolean $is_highlevel
 *
 * @property $relative_component_id
 * @property ?Component $component
 *
 * @property PhysicalObject $physicalObject
 * @property ?Component $relativeComponent
 * @property int $quantity_in_object
 * @property int $manufactor_start_way
 *
 * @property ?TechnicalTaskCalculation $technicalTaskCalculation
 * @property ?Sz $sz
 * @property ?PurchaseOrder $purchaseOrder
 *
 */
class Component extends Model
{
    use HasFactory;
    use AsSource;
    use Filterable;

    protected $fillable = [
        "title",
        'identifier',
        'source_type',
        'version',
        'type',
        'manufactor_start_way',
        'constructor_id',
        '3d_status',
        '3d_date_plan',
        'dd_status',
        'dd_date_plan',
        'drawing_files',
        'drawing_date',
        'calc_status',
        'calc_date_plan',
        'constructor_priority',
        'constructor_comment',
        'manufactor_id',
        'manufactor_status',
        'manufactor_date_plan',
        'manufactor_sz_files',
        'manufactor_sz_date',
        'manufactor_sz_quantity',
        'manufactor_priority',
        'manufactor_comment',
        'purchaser_id',
        'purchase_status',
        'purchase_date_plan',
        'purchase_request_files',
        'purchase_request_date',
        'purchase_request_quantity',
        'purchase_request_priority',
        'purchase_comment',
        'relative_component_id',
        'physical_object_id',
        'metasystem_id',
        'system_id',
        'subsystem_id',
        'quantity_in_object',
        'is_highlevel',

        'sz_id',
        'purchase_order_id',
        'technical_task_calculation_id',
        'drawing_files_id',
        'status'
    ];


    public function statusLabel(): string {
        return ComponentStatus::LABELS[$this->status] ?? '';
    }
    public function constructor()
    {
        return $this->belongsTo(User::class);
    }

    public function manufactor()
    {
        return $this->belongsTo(User::class);
    }

    public function purchaser()
    {
        return $this->belongsTo(User::class);
    }

    public function relativeComponent()
    {
        return $this->belongsTo(Component::class);
    }

    public function physicalObject()
    {
        return $this->belongsTo(PhysicalObject::class);
    }


    public function metasystem()
    {
        return $this->belongsTo(Metasystem::class);
    }

    public function system()
    {
        return $this->belongsTo(System::class);
    }

    public function subsystem()
    {
        return $this->belongsTo(Subsystem::class);
    }

    public function sz()
    {
        return $this->belongsTo(Sz::class);
    }

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }


    public function drawingFiles()
    {
        return $this->belongsTo(DrawingFile::class);
    }

    public function technicalTaskCalculation()
    {
        return $this->belongsTo(TechnicalTaskCalculation::class);
    }
    public function label(): string
    {
        return empty($this->identifier) ? $this->title :
            sprintf('%s (%s)', $this->title, $this->identifier);
    }

}
