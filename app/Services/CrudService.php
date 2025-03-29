<?php
declare(strict_types=1);

namespace App\Services;

use App\Enum\DeleteStatus;
use App\Interfaces\ServiceInterface;
use App\Repositories\CrudRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
/*
📌 abstract class: 
An abstract class provides a common base class that enforces a certain structure for all subclasses. 
If you have a group of classes that share common methods but may have different implementations, an abstract class ensures that all subclasses follow the same structure and implement the same methods.
Abstract classes are a way to implement the DRY (Don't Repeat Yourself) principle. You can implement shared logic in the abstract class and reuse it across all subclasses.
An abstract class can provide a template for subclasses to follow. It may include abstract methods (without implementation) that the subclasses must implement. 
You can extend an abstract class without modifying its internals, which ensures that existing functionality is preserved while adding or modifying only what’s needed.
The abstract keyword means that this class cannot be instantiated directly. You can only create instances of classes that extend this abstract class.
The class CrudService implements the ServiceInterface. This means that CrudService must either define the methods declared in ServiceInterface or leave them abstract for its child classes to implement.
*/

/*   
❓ When to Use an Abstract Class ? 
➡️ Shared functionality across multiple classes: If you have classes that need common methods but different implementations, use an abstract class to avoid code duplication.
➡️ Preventing instantiation of a base class
*/

/*   
📌 Abstract method:  
Shared functionality across multiple classes: If you have classes that need common methods but different implementations, use an abstract class to avoid code duplication.
An abstract method in an abstract class is a method that is declared but not implemented in that class.
*/


/*   
📌 Callable:  
This callable is used to generate a dynamic error message
This is useful when different parts of the application may need different error messages depending on the context, without modifying the core service.
*/

abstract class CrudService implements ServiceInterface
{
    protected $generateErrorMessage;

    /**
     * Entity Model.
     *
     * @var Model
     */
    protected $entity;

    public function __construct(
        protected CrudRepository $repository,
        callable $generateErrorMessage
    ) {
        $this->generateErrorMessage = $generateErrorMessage;
    }

    public function count(): int
    {
        return $this->repository->count();
    }


    public function authId(): int|null
    {
        return $this->repository->AuthId();
    }


    public function getById(int $id): ?object
    {
        $this->entity = $this->repository->find($id);
        if (!$this->entity) {
            $errorMessage = call_user_func($this->generateErrorMessage);
            throw new NotFoundHttpException($errorMessage);
        }
        return $this->entity;
    }

    public function findByWhere(array $keyValueArray)
    {
        return $this->repository->findByWhere($keyValueArray);
    }

    public function all(array $filters = [], ?array $orderBy = null): Collection
    {
        return $this->repository->all($filters, $orderBy);
    }

    public function getPaginatedData(
        ?int $perPage = null,
        array $filters = [],
        ?array $orderBy = null
    ): array|LengthAwarePaginator {
        return $this->repository->paginate($perPage, $filters, $orderBy);
    }


    public function create(array $data): object
    {
        return $this->repository->create($data);
    }

    public function update(int $id, array $data): object
    {
        $this->entity = $this->repository->find($id);
        if (!$this->entity) {
            $errorMessage = call_user_func($this->generateErrorMessage);
            throw new NotFoundHttpException($errorMessage);
        }
        return $this->repository->update($this->entity, $data);
    }

    public function delete(int $id, int $deleteStatus): ?object
    {
        $this->entity = $this->repository->find($id);

        if (!$this->entity) {
            $errorMessage = call_user_func($this->generateErrorMessage);
            throw new NotFoundHttpException($errorMessage);
        }

        // Check if model has the column deleted, then do soft/hard delete.
        $hasDelete = Schema::hasColumn($this->entity->getTable(), 'deleted');

        if ($hasDelete && $deleteStatus === DeleteStatus::NOT_DELETED->value) {
            return $this->repository->revertBack($this->entity);
        }

        if ($hasDelete && $deleteStatus === DeleteStatus::SOFT_DELETE->value) {
            return $this->repository->softDelete($this->entity);
        }

        // Otherwise delete totally from the model.
        return $this->repository->Delete($this->entity);
    }

}
