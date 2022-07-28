<?php

namespace App\Models\Component;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

/**
 * @property int $id
 * @property string $title
 * @property string $identifier
 * @property int $entry_level
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
 * @property \DateTime $drawing_date_plan
 * @property int $calc_status
 * @property \DateTime $calc_date_plan
 * @property string $tz_files
 * @property \DateTime $tz_date_plane
 * @property int $constructor_priority
 * @property string $constructor_comment

 * @property int $manufactor_id
 * @property ?User $manufactor
 * @property int $manufactor_status
 * @property \DateTime $manufactor_date_plan
 *
 * @property int $purchaser_id
 * @property ?User $purchaser
 *
 * @property Collection<int, PhysicalObject> $physicalObjects
 * @property ?Component $relativeComponent
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
        'constructor_id',
        'type',
        'relative_component_id'
        //"target",
    ];


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

    public function physicalObjects()
    {
        return $this->belongsToMany(PhysicalObject::class, 'components_physical_objects',
        'component_id', 'physical_object_id');
    }

    public function label(): string
    {
        return sprintf('%s (%s)', $this->title, $this->identifier);
    }

}
