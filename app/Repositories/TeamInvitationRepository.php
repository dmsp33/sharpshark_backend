<?php

namespace App\Repositories;

use App\Models\TeamInvitation;
use App\Repositories\BaseRepository;

/**
 * Class TeamInvitationRepository
 * @package App\Repositories
 * @version July 24, 2021, 3:33 pm UTC
*/

class TeamInvitationRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return TeamInvitation::class;
    }
}
