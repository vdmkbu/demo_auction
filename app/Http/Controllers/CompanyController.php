<?php

namespace App\Http\Controllers;

use antonshell\EgrulNalogParser\Parser;
use App\Models\Company;
use App\Repositories\CompanyRepository;
use App\Repositories\PdfDocumentRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;


class CompanyController extends Controller
{
    private Parser $parser;
    private CompanyRepository $repository;
    public function __construct(Parser $parser, CompanyRepository $repository)
    {
        $this->parser = $parser;
        $this->repository = $repository;
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

        $path = $request->file('file')->store('public');

        try {

            // парсим данные из загруженного pdf
            $result = $this->parser->parseDocument(Storage::path($path));
            $plain_text = $this->parser->getPlainText(Storage::path($path));

            $pdf = new PdfDocumentRepository($result, $plain_text);

            // получаем данные
            $name = $pdf->getName();
            $inn = $pdf->getINN();
            $date = $pdf->getDate();
            $okved = $pdf->getOKVED();



            // проверяем, есть ли в базе полученный ИНН
            if ($this->repository->isINNExists($inn)) {
                // ИНН есть в базе:
                // удаляем загруженный файл

                Storage::delete($path);
                return redirect(route('company.index'))->with('error', 'Ошибка! Предприятие с таким ИНН уже существует');
            }
            else {
                // ИНН нет в базе
                // добавляем новый объект

                Company::create([
                    'name' => $name,
                    'INN' => $inn,
                    'file' => $path,
                    'OKVED' => $okved,
                    'date' => $date,
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

        $path = $request->file('file')->store('public');

        try {

            // парсим данные из загруженного pdf
            $result = $this->parser->parseDocument(Storage::path($path));
            $plain_text = $this->parser->getPlainText(Storage::path($path));

            $pdf = new PdfDocumentRepository($result, $plain_text);

            // получаем данные
            $name = $pdf->getName();
            $inn = $pdf->getINN();
            $date = $pdf->getDate();
            $okved = $pdf->getOKVED();

            // нашли ИНН в текущем объекте  - можно обновить. если нашли такой ИНН в другом объекте, то обновить нельзя
            if ($company->INN == $inn) {

                $company->update([
                    'name' => $name,
                    'date' => $date,
                    'OKVED' => $okved
                ]);

                return redirect(route('company.index'))->with('success', 'Преприятие обновлено');
            }
            else {
                // удаляем загруженный файл
                Storage::delete($path);
                return redirect(route('company.index'))->with('error', 'Ошибка! Предприятие с таким ИНН уже существует');
            }

        } catch (\DomainException $exception) {
            logger()->error($exception->getMessage());
            return redirect(route('company.index'))->with('error', 'При обработке произошла ошибка');
        }

    }

}
