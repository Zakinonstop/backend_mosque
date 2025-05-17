<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\SettingCertificate;
use App\Http\Resources\ApiResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class SettingCertificateController
{
    private const STORAGE_FOLDER = 'certificate';

    private array $form_data = [
        'title',
        'sub_title1',
        'sub_title2',
        'description',
        'certifier',
        'position',
    ];

    private array $base_validator = [
        'title'         => 'required',
        'sub_title1'    => 'required',
        'sub_title2'    => 'required',
        'description'   => 'required',
        'certifier'     => 'required',
        'position'      => 'required',
    ];

    private function getValidationRules(string $function): array
    {
        $rules = $this->base_validator;

        $imageRule = 'image|mimes:jpeg,png,jpg,gif,svg|max:2048';

        if ($function === 'create') {
            $rules['background'] = 'required|' . $imageRule;
            $rules['logo'] = 'required|' . $imageRule;
        } else {
            $rules['background'] = 'nullable|' . $imageRule;
            $rules['logo'] = 'nullable|' . $imageRule;
        }

        return $rules;
    }

    public function index()
    {
        $setting_certificate = SettingCertificate::latest()->paginate(10);
        return new ApiResource(true, 'Data retrieved successfully.', $setting_certificate);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->getValidationRules('create'));
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $background = $request->file('background');
        $logo = $request->file('logo');

        $background->storeAs(self::STORAGE_FOLDER, $background->hashName());
        $logo->storeAs(self::STORAGE_FOLDER, $logo->hashName());

        $data_input = $request->only($this->form_data);
        $data_input['background'] = $background->hashName();
        $data_input['logo'] = $logo->hashName();

        $setting_certificate = SettingCertificate::create($data_input);

        return new ApiResource(true, 'Data created successfully.', $setting_certificate);
    }

    public function show($id)
    {
        $setting_certificate = SettingCertificate::find($id);
        if (!$setting_certificate) {
            return new ApiResource(false, 'Data not found!', null);
        }

        return new ApiResource(true, 'Data details retrieved successfully.', $setting_certificate);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), $this->getValidationRules('update'));
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $setting_certificate = SettingCertificate::find($id);
        if (!$setting_certificate) {
            return new ApiResource(false, 'Data not found!', null);
        }

        $data_input = $request->only($this->form_data);

        if ($request->hasFile('background')) {
            Storage::delete(self::STORAGE_FOLDER . '/' . $setting_certificate->background);
            $background = $request->file('background');
            $background->storeAs(self::STORAGE_FOLDER, $background->hashName());
            $data_input['background'] = $background->hashName();
        }

        if ($request->hasFile('logo')) {
            Storage::delete(self::STORAGE_FOLDER . '/' . $setting_certificate->logo);
            $logo = $request->file('logo');
            $logo->storeAs(self::STORAGE_FOLDER, $logo->hashName());
            $data_input['logo'] = $logo->hashName();
        }

        $setting_certificate->update($data_input);

        return new ApiResource(true, 'Data updated successfully!', $setting_certificate);
    }

    public function destroy($id)
    {
        $setting_certificate = SettingCertificate::find($id);
        if (!$setting_certificate) {
            return new ApiResource(false, 'Data not found!', null);
        }

        Storage::delete([
            self::STORAGE_FOLDER . '/' . $setting_certificate->background,
            self::STORAGE_FOLDER . '/' . $setting_certificate->logo
        ]);

        $setting_certificate->delete();

        return new ApiResource(true, 'Data deleted successfully!', null);
    }
}
