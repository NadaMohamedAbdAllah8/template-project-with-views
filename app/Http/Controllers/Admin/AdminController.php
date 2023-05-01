<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Admin\Admin\DataAction;
use App\Actions\Admin\Admin\DestroyAction;
use App\Actions\Admin\Admin\StoreAction;
use App\Actions\Admin\Admin\UpdateAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Admin\StoreRequest;
use App\Http\Requests\Admin\Admin\UpdateRequest;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return redirect()->route('admin.admins.edit', 1);
        $data = [
            'title' => 'Admins',
        ];

        return view('admin.pages.admins.index', $data);
    }

    public function data(Request $request, DataAction $data_action)
    {
        return Datatables::of($data_action->execute($request))->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [
            'title' => 'Create Admin',
        ];
        return view('admin.pages.admins.create', $data);
    }

/**
 * Store a newly created resource in storage.
 *
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\Http\Response
 */
    public function store(StoreRequest $request, StoreAction $store_action)
    {
        DB::beginTransaction();
        try {
            $store_action->execute($request);
            DB::commit();
            return redirect(route('admin.admins.index'))
                ->with('success', 'Admin Created');
        } catch (\Exception $e) {
            DB::rollback();

            return redirect(route('admin.admins.index'))
                ->with('error', 'Error! Cannot create');
        }
    }

/**
 * Display the specified resource.
 *
 * @param  int  $id
 * @return \Illuminate\Http\Response
 */
    public function show(Admin $admin)
    {
        $data = [
            'title' => 'Edit Admin',
            'admin' => $admin,
        ];
        return view('admin.pages.admins.show', $data);
    }

/**
 * Show the form for editing the specified resource.
 *
 * @param  int  $id
 * @return \Illuminate\Http\Response
 */
    public function edit(Admin $admin)
    {
        $data = [
            'title' => 'Edit Admin',
            'admin' => $admin,
        ];
        return view('admin.pages.admins.edit', $data);
    }

/**
 * Update the specified resource in storage.
 *
 * @param  \Illuminate\Http\Request  $request
 * @param  int  $id
 * @return \Illuminate\Http\Response
 */
    public function update(UpdateRequest $request, Admin $admin, UpdateAction $update_action)
    {
        DB::beginTransaction();
        try {
            $update_action->execute($request, $admin);
            DB::commit();

            return redirect(route('admin.admins.index'))
                ->with('success', 'Admin Updated');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect(route('admin.admins.index'))
                ->with('error', 'Error! Cannot update');
        }
    }

/**
 * Remove the specified resource from storage.
 *
 * @param  int  $id
 * @return \Illuminate\Http\Response
 */
    public function destroy(DestroyAction $destroy_action, Admin $admin)
    {
        DB::beginTransaction();
        try {
            $destroy_action->execute($admin);
            DB::commit();
            return response()->json(['success' => 'Done'], 204)
                ->header('Content-Type', 'application/json');
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Cannot delete!', 'id' => $admin->id], 500)
                ->header('Content-Type', 'application/json');
        }
    }
}
