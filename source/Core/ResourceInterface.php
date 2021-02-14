<?php


namespace Source\Core;


use Toniette\Router\Request;

/**
 * Interface ResourceInterface
 * @package Source\Core
 */
interface ResourceInterface
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
    public function create(Request $req): void;

    /**
     * @param Request $req
     */
    public function store(Request $req): void;

    /**
     * @param Request $req
     */
    public function edit(Request $req): void;

    /**
     * @param Request $req
     */
    public function update(Request $req): void;

    /**
     * @param Request $req
     */
    public function destroy(Request $req): void;
}