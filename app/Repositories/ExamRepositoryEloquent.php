<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\ExamRepository;
use App\Entities\Exam;
use App\Validators\ExamValidator;

/**
 * Class ExamRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class ExamRepositoryEloquent extends BaseRepository implements ExamRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Exam::class;
    }



    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

}
