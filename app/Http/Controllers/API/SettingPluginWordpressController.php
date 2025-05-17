<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\SettingPluginWordpress;
use App\Http\Resources\ApiResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;


class SettingPluginWordpressController
{
    private const STORAGE_FOLDER = 'plugin_wordpress';

    private array $form_data = [
        'plugin_name',
        'price_of_brick',
        'target_brick',
    ];

    private array $base_validator = [
        'plugin_name' => 'required',
        'price_of_brick' => 'required',
        'target_brick' => 'required',
    ];

    private function getValidationRules(string $function): array
    {
        $rules = $this->base_validator;

        $imageRule = 'image|mimes:jpeg,png,jpg,gif,svg|max:2048';

        if ($function === 'create') {
            $rules['background'] = 'required|' . $imageRule;
        } else {
            $rules['background'] = 'nullable|' . $imageRule;
        }

        return $rules;
    }

    public function index()
    {
        $setting_plugin = SettingPluginWordpress::latest()->paginate(10);
        return new ApiResource(true, 'Data retrieved successfully.', $setting_plugin);
    }

    public function getTargetBrick()
    {
        $targetBrick = SettingPluginWordpress::latest()->value('target_brick');

        return new ApiResource(true, 'Target brick retrieved successfully.', $targetBrick);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->getValidationRules('create'));
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $background = $request->file('background');

        $background->storeAs(self::STORAGE_FOLDER, $background->hashName());

        $data_input = $request->only($this->form_data);
        $data_input['background'] = $background->hashName();

        $setting_plugin = SettingPluginWordpress::create($data_input);

        return new ApiResource(true, 'Data created successfully.', $setting_plugin);
    }

    public function show($id)
    {
        $setting_plugin = SettingPluginWordpress::find($id);
        if (!$setting_plugin) {
            return new ApiResource(false, 'Data not found!', null);
        }

        return new ApiResource(true, 'Data details retrieved successfully.', $setting_plugin);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), $this->getValidationRules('update'));
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $setting_plugin = SettingPluginWordpress::find($id);
        if (!$setting_plugin) {
            return new ApiResource(false, 'Data not found!', null);
        }

        $data_input = $request->only($this->form_data);

        if ($request->hasFile('background')) {
            Storage::delete(self::STORAGE_FOLDER . '/' . $setting_plugin->background);
            $background = $request->file('background');
            $background->storeAs(self::STORAGE_FOLDER, $background->hashName());
            $data_input['background'] = $background->hashName();
        }

        $setting_plugin->update($data_input);

        return new ApiResource(true, 'Data updated successfully!', $setting_plugin);
    }

    public function destroy($id)
    {
        $setting_plugin = SettingPluginWordpress::find($id);
        if (!$setting_plugin) {
            return new ApiResource(false, 'Data not found!', null);
        }

        Storage::delete([
            self::STORAGE_FOLDER . '/' . $setting_plugin->background,
        ]);

        $setting_plugin->delete();

        return new ApiResource(true, 'Data deleted successfully!', null);
    }
}
