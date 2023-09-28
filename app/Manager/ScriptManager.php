<?php
namespace App\Manager;

use Illuminate\Support\Facades\Http;
use App\Models\Country;
use App\Models\Division;
use App\Models\District;
use App\Models\Area;

class ScriptManager
{
	
	public function getLocationData()
	{
		$division_url="example.com";
		$div_response = Http::get($division_url);
		$divisions = json_decode($div_response->body(),true);
		foreach ($divisions as $key => $division) {
			if($key == 0){
				$division_data['name'] = $division['name']; 
				$division_data['original_id'] = $division['id'];
				$division_id = Division::create($division_data);

				$district_url="example.com/".$division_id;
				$dist_response = Http::get($district_url);
				$districts = json_decode($dist_response->body(),true);
				foreach ($districts as $key => $district) {
					$district_data['division_id'] = $division_id->id; 
					$district_data['name'] = $district['name']; 
					$district_data['original_id'] = $district['id'];
					$district_id = District::create($district_data);

					$area_url="example.com/".$district_id;
					$area_response = Http::get($area_url);
					$areas = json_decode($area_response->body(),true);
					foreach ($areas as $key => $area) {
						$area_data['district_id'] = $district_id->id; 
						$area_data['name'] = $area['name']; 
						$area_data['original_id'] = $area['id'];
						Area::create($area_data);
					}

				}

			}
		}

	}

	public function getCountry()
	{
		$country_url = "https://restcountries.com/v3.1/all";
		$country_response = Http::get($country_url);
		$countries = json_decode($country_response->body(),true);
		foreach ($countries as $country) {
			$country_data['name'] = $country['name']['common'];
			Country::create($country_data);
		}
		echo "Country inserted successfully";
	}


	
}
?>