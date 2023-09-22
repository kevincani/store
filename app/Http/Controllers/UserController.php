<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{

    /** Deklarimi i repozitorit qe do te perdoret */
    protected UserRepository $userRepository;
    public function __construct(UserRepository $repository){
        $this->userRepository = $repository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('user.index');
    }

    public function getForDatatable(): JsonResponse
    {
        $data = $this->userRepository->allUsersWithRoles();

        return DataTables::of($data)->addIndexColumn()
            ->addColumn('action', function ($data) {
                $button = '<button type="button" id="'.$data->id.'" name="edit"
                            class="text-white bg-blue-700 hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-full text-sm px-5 py-2.5 text-center mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                            Edit</button>';

                $button .= '<button type="button" id="'.$data->id.'" name="delete"
                            class="text-white bg-red-700 hover:bg-red-800 focus:outline-none focus:ring-4 focus:ring-red-300 font-medium rounded-full text-sm px-5 py-2.5 text-center mr-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900" style="background-color: red ">
                            Delete</button>';
                return $button;
            })
            ->addColumn('role',function($data){
                return $data->getRoleNames()->first();
            })
            ->make();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        $data = $request->validated();
        unset($data['password_confirmation']);
        $user = $this->userRepository->create($data);
        $user->assignRole('client');

        return response()->json(['message' => 'User created successfully']);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, User $user): void
    {
        $data = $request->validated();
        $this->userRepository->update($user,$data);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): void
    {
        $this->userRepository->delete($user);
    }
}
