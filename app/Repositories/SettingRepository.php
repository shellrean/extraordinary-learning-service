<?php

namespace App\Repositories;

use App\Setting;

class SettingRepository
{
	/**
	 * Data setting
	 * @var \App\Setting
	 */
	private $setting;

	/**
	 * Retreive data setting
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @return \App\Setting
	 */
	public function getSetting()
	{
		return $this->setting;
	}

	/**
	 * Set data setting property
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @return void
	 */
	public function setSetting($setting)
	{
		$this->setting = $setting;
	}

	/**
	 * Get data setting
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @param $setting_name
	 * @return void
	 */
	public function getDataSetting($setting_name)
	{
		try {
			$setting = Setting::where('name', $setting_name)->first();
			$this->setSetting($setting);
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}

	/**
	 * Set setting data
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @param \App\Http\Requests\SettingStore
	 * @return void
	 */
	public function createOrUpdateSetting($request): void
	{
		try {
			$this->getDataSetting($request->name);
			$setting = $this->getSetting();

			if(!$setting) {
				$setting = Setting::create([
					'name'	=> $request->name,
					'type'	=> 'general',
					'settings' => $request->settings
				]);
				$this->setSetting($setting);
				return;
			}
			$settings = collect($setting->settings);
			$replaced = $settings->replace($request->settings);
			$setting->update([
				'settings'	=> $replaced->toArray()
			]);
			$this->setSetting($setting);
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}
}