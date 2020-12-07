<?php

namespace App\Http\Controllers;


use App\Models\Company;
use App\Repositories\CompanyRepository;
use App\Services\FileSystemManager;
use App\Services\FileUploader;
use App\Services\Pdf\PdfParserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;


class CompanyController extends Controller
{
    private CompanyRepository $repository;
    private PdfParserService $parser;
    private FileUploader $uploader;
    private FileSystemManager $fileSystem;

    public function __construct(PdfParserService $parser, CompanyRepository $repository, FileUploader $uploader, FileSystemManager $fileSystem)
    {
        $this->parser = $parser;
        $this->repository = $repository;
        $this->uploader = $uploader;
        $this->fileSystem = $fileSystem;
    }

    public function index()
    {
        $companies = Company::owner(auth()->id())->get();
        return view('company.index', compact('companies'));
    }

    public function create()
    {
        $method = 'post';
        return view('company.create', compact('method'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|file|mimetypes:application/pdf|max:3072'
        ]);

        $path = $this->uploader->upload($request->file('file'));

        try {

            $pdf = $this->parser->parseDocument($this->fileSystem->path($path));

            // проверяем, есть ли в базе полученный ИНН
            if ($this->repository->isINNExists($pdf->inn)) {
                // ИНН есть в базе:
                // удаляем загруженный файл

                $this->fileSystem->delete($path);
                return redirect(route('company.index'))->with('error', 'Ошибка! Предприятие с таким ИНН уже существует');
            }
            else {
                // ИНН нет в базе
                // добавляем новый объект

                Company::create([
                    'name' => $pdf->name,
                    'INN' => $pdf->inn,
                    'file' => $path,
                    'OKVED' => $pdf->okved,
                    'date' => $pdf->date,
                    'user_id' => auth()->id(),
                ]);

                return redirect(route('company.index'))->with('success', 'Преприятие добавлено');
            }

        } catch (\DomainException $exception) {
            logger()->error($exception->getMessage());
            return redirect(route('company.index'))->with('error', 'При обработке произошла ошибка');
        }


    }

    public function show(Company $company)
    {
        if (Gate::denies('company_owner', $company->user_id)) {
            abort(403, 'Access denied');
        }

        $download_link = Storage::url($company->file);
        return view('company.show', compact('company', 'download_link'));
    }

    public function edit(Company $company)
    {
        if (Gate::denies('company_owner', $company->user_id)) {
            abort(403, 'Access denied');
        }

        $method = 'put';
        return view('company.create', compact('method', 'company'));
    }

    public function update(Company $company, Request $request)
    {

        if (Gate::denies('company_owner', $company->user_id)) {
            abort(403, 'Access denied');
        }

        $this->validate($request, [
            'file' => 'required|file|mimetypes:application/pdf|max:3072'
        ]);

        $path = $this->uploader->upload($request->file('file'));

        try {

            $pdf = $this->parser->parseDocument($this->fileSystem->path($path));

            // нашли ИНН в текущем объекте  - можно обновить. если нашли такой ИНН в другом объекте, то обновить нельзя
            if ($company->INN == $pdf->inn) {

                $company->update([
                    'name' => $pdf->name,
                    'date' => $pdf->date,
                    'OKVED' => $pdf->okved
                ]);

                return redirect(route('company.index'))->with('success', 'Преприятие обновлено');
            }
            else {
                // удаляем загруженный файл
                $this->fileSystem->delete($path);
                return redirect(route('company.index'))->with('error', 'Ошибка! Предприятие с таким ИНН уже существует');
            }

        } catch (\DomainException $exception) {
            logger()->error($exception->getMessage());
            return redirect(route('company.index'))->with('error', 'При обработке произошла ошибка');
        }

    }

    public function destroy(Company $company)
    {
        if (Gate::denies('company_owner', $company->user_id)) {
            abort(403, 'Access denied');
        }

        $this->fileSystem->delete($company->file);
        $company->delete();

        return redirect(route('company.index'))->with('success', 'Предприятие удалено');
    }

}
