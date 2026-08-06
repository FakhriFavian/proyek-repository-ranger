<?php
namespace App\Modules\borrowings\Controllers;

use App\Helpers\Logger;
use Illuminate\Http\Request;
use App\Modules\Log\Models\Log;
use App\Modules\borrowings\Models\borrowings;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class borrowingsController extends Controller
{
	use Logger;
	protected $log;
	protected $title = "Borrowings";

	public function __construct(Log $log)
	{
		$this->log = $log;
	}

	public function index(Request $request)
	{
		$query = borrowings::query();
		if($request->has('search')){
			$search = $request->get('search');
			// $query->where('name', 'like', "%$search%");
		}
		$data['data'] = $query->paginate(10)->withQueryString();

		$this->log($request, 'melihat halaman manajemen data '.$this->title);
		return view('borrowings::borrowings', array_merge($data, ['title' => $this->title]));
	}

	public function create(Request $request)
	{
		
		$data['forms'] = array(
			
		);

		$this->log($request, 'membuka form tambah '.$this->title);
		return view('borrowings::borrowings_create', array_merge($data, ['title' => $this->title]));
	}

	function store(Request $request)
	{
		$this->validate($request, [
			
		]);

		$borrowings = new borrowings();
		
		$borrowings->created_by = Auth::id();
		$borrowings->save();

		$text = 'membuat '.$this->title; //' baru '.$borrowings->what;
		$this->log($request, $text, ['borrowings.id' => $borrowings->id]);
		return redirect()->route('borrowings.index')->with('message_success', 'Borrowings berhasil ditambahkan!');
	}

	public function show(Request $request, borrowings $borrowings)
	{
		$data['borrowings'] = $borrowings;

		$text = 'melihat detail '.$this->title;//.' '.$borrowings->what;
		$this->log($request, $text, ['borrowings.id' => $borrowings->id]);
		return view('borrowings::borrowings_detail', array_merge($data, ['title' => $this->title]));
	}

	public function edit(Request $request, borrowings $borrowings)
	{
		$data['borrowings'] = $borrowings;

		
		$data['forms'] = array(
			
		);

		$text = 'membuka form edit '.$this->title;//.' '.$borrowings->what;
		$this->log($request, $text, ['borrowings.id' => $borrowings->id]);
		return view('borrowings::borrowings_update', array_merge($data, ['title' => $this->title]));
	}

	public function update(Request $request, $id)
	{
		$this->validate($request, [
			
		]);

		$borrowings = borrowings::find($id);
		
		$borrowings->updated_by = Auth::id();
		$borrowings->save();


		$text = 'mengedit '.$this->title;//.' '.$borrowings->what;
		$this->log($request, $text, ['borrowings.id' => $borrowings->id]);
		return redirect()->route('borrowings.index')->with('message_success', 'Borrowings berhasil diubah!');
	}

	public function destroy(Request $request, $id)
	{
		$borrowings = borrowings::find($id);
		$borrowings->deleted_by = Auth::id();
		$borrowings->save();
		$borrowings->delete();

		$text = 'menghapus '.$this->title;//.' '.$borrowings->what;
		$this->log($request, $text, ['borrowings.id' => $borrowings->id]);
		return back()->with('message_success', 'Borrowings berhasil dihapus!');
	}

}
