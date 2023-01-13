<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait SanitizedRequest
{

    private $clean = false;

    public function sanitize_request(Request $request)
    {
        return $this->sanitize($request);
    }

    protected function sanitize($request)
    {
        if ($this->clean) {
            return $request;
        }

        $input = $request->all();
        array_walk_recursive($input, function (&$input) {
            $input = strip_tags($input);
            //$input = mysql_real_escape_string($input);
        });
        $request->merge($input);
        $this->clean = true;
        return $request;
    }
}
