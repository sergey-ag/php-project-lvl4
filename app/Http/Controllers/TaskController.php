<?php

namespace Craftworks\TaskManager\Http\Controllers;

use Craftworks\TaskManager\Task;
use Illuminate\Http\Request;
use Craftworks\TaskManager\TaskStatus;
use Craftworks\TaskManager\User;
use Craftworks\TaskManager\Tag;

class TaskController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filter = [
            'statusFilter' => $request->input('statusFilter') ?? [],
            'tagFilter' => $request->input('tagFilter') ?? [],
            'userFilter' => $request->input('userFilter') ?? []
        ];
        
        $tasks = Task::getFiltered($filter)->paginate(10);
        $users = User::orderBy('name')->get();
        $statuses = TaskStatus::orderBy('name')->get();
        $tags = Tag::orderBy('name')->get();
        
        return view('tasks.index', [
            'users' => $users,
            'statuses' => $statuses,
            'tags' => $tags,
            'tasks' => $tasks,
            'statusFilter' => $filter['statusFilter'],
            'userFilter' => $filter['userFilter'],
            'tagFilter' => $filter['tagFilter']
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $taskStatuses = TaskStatus::all();
        $users = User::all();
        return view('tasks.create', [
            'statuses' => $taskStatuses,
            'users' => $users
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => [],
            'status_id' => ['exists:task_statuses,id'],
            'assigned_to_id' => ['nullable', 'exists:users,id']
        ]);

        $data['creator_id'] = auth()->user()->id;
        $tags = Tag::getIds($request->tags);

        Task::create($data)->tags()->sync($tags);

        return redirect()->route('tasks.index')->with('success', __('The task has been created!'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \Craftworks\TaskManager\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task)
    {
        return view('tasks.show', ['task' => $task]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Craftworks\TaskManager\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $task)
    {
        $taskStatuses = TaskStatus::all();
        $users = User::all();
        return view('tasks.edit', [
            'task' => $task,
            'statuses' => $taskStatuses,
            'users' => $users
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Craftworks\TaskManager\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Task $task)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => [],
            'status_id' => ['exists:task_statuses,id'],
            'assigned_to_id' => ['nullable', 'exists:users,id']
        ]);

        $tags = Tag::getIds($request->tags);

        $task->name = $data['name'];
        $task->description = $data['description'];
        $task->status_id = $data['status_id'];
        $task->assigned_to_id = $data['assigned_to_id'];
        $task->tags()->sync($tags);

        $task->save();

        return redirect()->route('tasks.index')->with('success', __('The task has been updated!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Craftworks\TaskManager\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        $task->tags()->detach();
        $task->delete();

        return redirect()->route('tasks.index')->with('success', __('The task has been deleted'));
    }
}
