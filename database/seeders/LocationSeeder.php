<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Country;
use App\Models\State;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        $india = Country::create(['name' => 'India', 'code' => 'IN']);

        $states = [
            'Gujarat' => ['Ahmedabad', 'Surat', 'Vadodara', 'Rajkot', 'Gandhinagar', 'Bhavnagar', 'Jamnagar', 'Junagadh', 'Anand', 'Mehsana'],
            'Maharashtra' => ['Mumbai', 'Pune', 'Nagpur', 'Thane', 'Nashik', 'Aurangabad', 'Solapur', 'Kolhapur', 'Navi Mumbai'],
            'Delhi' => ['New Delhi', 'Delhi'],
            'Karnataka' => ['Bangalore', 'Mysore', 'Hubli', 'Mangalore', 'Belgaum', 'Gulbarga'],
            'Tamil Nadu' => ['Chennai', 'Coimbatore', 'Madurai', 'Tiruchirappalli', 'Salem', 'Tirunelveli'],
            'Telangana' => ['Hyderabad', 'Warangal', 'Nizamabad', 'Karimnagar', 'Khammam'],
            'West Bengal' => ['Kolkata', 'Howrah', 'Durgapur', 'Asansol', 'Siliguri'],
            'Rajasthan' => ['Jaipur', 'Jodhpur', 'Udaipur', 'Kota', 'Ajmer', 'Bikaner'],
            'Uttar Pradesh' => ['Lucknow', 'Noida', 'Agra', 'Varanasi', 'Kanpur', 'Ghaziabad', 'Meerut', 'Prayagraj'],
            'Madhya Pradesh' => ['Bhopal', 'Indore', 'Jabalpur', 'Gwalior', 'Ujjain'],
            'Kerala' => ['Thiruvananthapuram', 'Kochi', 'Kozhikode', 'Thrissur', 'Kollam'],
            'Punjab' => ['Chandigarh', 'Ludhiana', 'Amritsar', 'Jalandhar', 'Patiala'],
            'Haryana' => ['Gurugram', 'Faridabad', 'Panipat', 'Ambala', 'Karnal'],
            'Bihar' => ['Patna', 'Gaya', 'Bhagalpur', 'Muzaffarpur', 'Darbhanga'],
            'Odisha' => ['Bhubaneswar', 'Cuttack', 'Rourkela', 'Berhampur'],
            'Andhra Pradesh' => ['Visakhapatnam', 'Vijayawada', 'Guntur', 'Nellore', 'Tirupati'],
        ];

        foreach ($states as $stateName => $cities) {
            $state = State::create(['country_id' => $india->id, 'name' => $stateName]);
            foreach ($cities as $cityName) {
                City::create(['state_id' => $state->id, 'name' => $cityName]);
            }
        }

        $usa = Country::create(['name' => 'United States', 'code' => 'US']);
        $usStates = [
            'California' => ['Los Angeles', 'San Francisco', 'San Diego', 'San Jose'],
            'New York' => ['New York City', 'Buffalo', 'Rochester'],
            'Texas' => ['Houston', 'Dallas', 'Austin', 'San Antonio'],
            'Florida' => ['Miami', 'Orlando', 'Tampa', 'Jacksonville'],
        ];
        foreach ($usStates as $stateName => $cities) {
            $state = State::create(['country_id' => $usa->id, 'name' => $stateName]);
            foreach ($cities as $cityName) {
                City::create(['state_id' => $state->id, 'name' => $cityName]);
            }
        }

        $uk = Country::create(['name' => 'United Kingdom', 'code' => 'GB']);
        $ukRegions = [
            'England' => ['London', 'Manchester', 'Birmingham', 'Leeds', 'Liverpool'],
            'Scotland' => ['Edinburgh', 'Glasgow'],
        ];
        foreach ($ukRegions as $stateName => $cities) {
            $state = State::create(['country_id' => $uk->id, 'name' => $stateName]);
            foreach ($cities as $cityName) {
                City::create(['state_id' => $state->id, 'name' => $cityName]);
            }
        }

        $uae = Country::create(['name' => 'United Arab Emirates', 'code' => 'AE']);
        $uaeStates = [
            'Dubai' => ['Dubai'],
            'Abu Dhabi' => ['Abu Dhabi'],
            'Sharjah' => ['Sharjah'],
        ];
        foreach ($uaeStates as $stateName => $cities) {
            $state = State::create(['country_id' => $uae->id, 'name' => $stateName]);
            foreach ($cities as $cityName) {
                City::create(['state_id' => $state->id, 'name' => $cityName]);
            }
        }

        $canada = Country::create(['name' => 'Canada', 'code' => 'CA']);
        $caStates = [
            'Ontario' => ['Toronto', 'Ottawa', 'Mississauga'],
            'British Columbia' => ['Vancouver', 'Victoria'],
            'Quebec' => ['Montreal', 'Quebec City'],
        ];
        foreach ($caStates as $stateName => $cities) {
            $state = State::create(['country_id' => $canada->id, 'name' => $stateName]);
            foreach ($cities as $cityName) {
                City::create(['state_id' => $state->id, 'name' => $cityName]);
            }
        }

        $australia = Country::create(['name' => 'Australia', 'code' => 'AU']);
        $auStates = [
            'New South Wales' => ['Sydney', 'Newcastle'],
            'Victoria' => ['Melbourne', 'Geelong'],
            'Queensland' => ['Brisbane', 'Gold Coast'],
        ];
        foreach ($auStates as $stateName => $cities) {
            $state = State::create(['country_id' => $australia->id, 'name' => $stateName]);
            foreach ($cities as $cityName) {
                City::create(['state_id' => $state->id, 'name' => $cityName]);
            }
        }
    }
}
