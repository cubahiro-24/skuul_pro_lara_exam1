<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ServiceStoreRequest;
use App\Http\Requests\Admin\ServiceUpdateRequest;
use App\Models\Service;
use App\Models\TypeService;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::withCount('typeServices')->latest()->paginate(12);
        return view('admin.services.index', compact('services'));
    }

    public function create()
    {
        return view('admin.services.create');
    }

    public function store(ServiceStoreRequest $request)
    {
        $data = $request->validated();
        $typeServices = $data['type_services'] ?? [];
        unset($data['type_services']);

        $service = Service::create($data);

        foreach ($typeServices as $ts) {
            if (empty(array_filter($ts))) continue;
            $service->typeServices()->create([
                'nom' => $ts['nom'] ?? null,
                'description' => $ts['description'] ?? null,
                'prix' => $ts['prix'] ?? 0,
                'duree_minutes' => $ts['duree_minutes'] ?? null,
            ]);
        }

        return redirect()->route('admin.services.index')->with('success', 'Service créé avec succès.');
    }

    public function show(Service $service)
    {
        $service->load('typeServices');
        return view('admin.services.show', compact('service'));
    }

    public function edit(Service $service)
    {
        $service->load('typeServices');
        return view('admin.services.edit', compact('service'));
    }

    public function update(ServiceUpdateRequest $request, Service $service)
    {
        $data = $request->validated();
        $incoming = $data['type_services'] ?? [];
        unset($data['type_services']);

        $service->update($data);

        $existingIds = $service->typeServices()->pluck('id')->toArray();
        $incomingIds = [];

        foreach ($incoming as $ts) {
            if (!empty($ts['id'])) {
                $incomingIds[] = (int) $ts['id'];
                $type = TypeService::find($ts['id']);
                if ($type) {
                    $type->update([
                        'nom' => $ts['nom'] ?? $type->nom,
                        'description' => $ts['description'] ?? $type->description,
                        'prix' => $ts['prix'] ?? $type->prix,
                        'duree_minutes' => $ts['duree_minutes'] ?? $type->duree_minutes,
                    ]);
                }
                continue;
            }

            if (empty(array_filter($ts))) continue;
            $created = $service->typeServices()->create([
                'nom' => $ts['nom'] ?? null,
                'description' => $ts['description'] ?? null,
                'prix' => $ts['prix'] ?? 0,
                'duree_minutes' => $ts['duree_minutes'] ?? null,
            ]);
            $incomingIds[] = $created->id;
        }

        $toDelete = array_diff($existingIds, $incomingIds);
        if (!empty($toDelete)) {
            TypeService::whereIn('id', $toDelete)->delete();
        }

        return redirect()->route('admin.services.index')->with('success', 'Service mis à jour.');
    }

    public function destroy(Service $service)
    {
        $service->typeServices()->delete();
        $service->delete();

        return redirect()->route('admin.services.index')->with('success', 'Service supprimé.');
    }
}
