<?php


namespace Source\Core;

use Toniette\Router\Request;

/**
 * Interface ApiResourceInterface
 * @package Source\Core
 */
interface ApiResourceInterface
{
    /**
     * @param Request $req
     */
    public function index(Request $req): void;

    /**
     * @param Request $req
     */
    public function show(Request $req): void;

    /**
     * @param Request $req
     */
    public function store(Request $req): void;

    /**
     * @param Request $req
     */
    public function update(Request $req): void;

    /**
     * @param Request $req
     */
    public function destroy(Request $req): void;
}