<?php namespace Michaeljennings\Carpenter\Pagination;

use Illuminate\Pagination\BootstrapThreePresenter;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class IlluminateDriver implements PaginatorInterface {

    /**
     * An instance of the IOC container.
     *
     * @var mixed
     */
    protected $app;

    /**
     * The illuminate paginator.
     *
     * @var mixed
     */
    protected $paginator;

    public function __construct($app)
    {
        $this->app = $app;
    }

    /**
     * Create a new paginator.
     *
     * @param  string|integer $total
     * @param  string|integer $perPage
     * @return mixed
     */
    public function make($total, $perPage)
    {
        $this->paginator = new LengthAwarePaginator(array(), $total, $perPage, $this->app['request']->input('page'), [
            'path' => $this->app['request']->url(),
        ]);
        return $this->paginator;
    }

    /**
     * Get the pagination links.
     *
     * @return string
     */
    public function links()
    {
        return $this->paginator->render(new BootstrapThreePresenter($this->paginator));
    }
} 