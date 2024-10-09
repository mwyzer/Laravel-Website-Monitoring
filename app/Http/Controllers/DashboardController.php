<?php

namespace App\Http\Controllers;

use App\Models\RouterosAPI;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    private RouterosAPI $api;

    public function __construct(RouterosAPI $api)
    {
        $this->api = $api;
    }

    public function index(): \Illuminate\Contracts\View\View
    {
        $ip = '192.168.1.100';
        $user = 'admin';
        $password = 'admin';

        $this->api->debug = false;

        $data = [];
        if ($this->api->connect($ip, $user, $password)) {
        // Retrieve system identity
        $identity = $this->api->comm('/system/identity/print');

        // Retrieve system resource information
        $resource = $this->api->comm('/system/resource/print');
        
        // Retrieve routerboard information
        $routerboard = $this->api->comm('/system/routerboard/print');

        $this->api->disconnect();

        // Merge data
        $data = [
            'identity' => $identity[0]['name'],
            'resource' => $resource[0],
            'routerboard' => $routerboard[0],
            'error' => null
        ];
        } else {
        $data['error'] = 'Connection failed';
        }
        dd($identity);
        dd($resource);

        return view('dashboard', compact('data'));
    }
}
