<?php

/**
*    Copyright 2015 ppy Pty. Ltd.
*
*    This file is part of osu!web. osu!web is distributed with the hope of
*    attracting more community contributions to the core ecosystem of osu!.
*
*    osu!web is free software: you can redistribute it and/or modify
*    it under the terms of the Affero GNU General Public License as published by
*    the Free Software Foundation, either version 3 of the License, or
*    (at your option) any later version.
*
*    osu!web is distributed WITHOUT ANY WARRANTY; without even the implied
*    warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*    See the GNU Affero General Public License for more details.
*
*    You should have received a copy of the GNU Affero General Public License
*    along with osu!web.  If not, see <http://www.gnu.org/licenses/>.
*
*/

namespace App\Http\Controllers;

use App\Models\BeatmapSet;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use App\Transformers\BeatmapTransformer;

class BeatmapController extends Controller {

	protected $section = "beatmaps";


	public function index()
	{
		$fractal = new Manager();
		$data = new Collection(BeatmapSet::listing(), new BeatmapTransformer);
		$beatmaps = $fractal->createData($data)->toArray();
		return view("beatmaps.index", compact('beatmaps'));
	}

}
