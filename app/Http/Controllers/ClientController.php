<?php
namespace App\Http\Controllers;

use App\Http\Requests\CreateClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\User;
use App\Repositories\ClientRepository;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;
use App\Exports\ClientsExport;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\Response;
use Barryvdh\DomPDF\Facade\Pdf;


class ClientController extends AppBaseController
{
    /**
     * @var ClientRepository
     */
    private $clientRepository;

    public function __construct(ClientRepository $clientRepo)
    {
        $this->clientRepository = $clientRepo;
    }

    /**
     * @return Application|Factory|View
     *
     * @throws Exception
     */
    public function index(Request $request): \Illuminate\View\View
    {
        return view('clients.index');
    }

    /**
     * @return Application|Factory|View
     */
    public function create(): \Illuminate\View\View
    {
        $data = $this->clientRepository->getData();
        $countries = $data['countries'];
        $vatNoLabel = getVatNoLabel();

        return view('clients.create', compact('countries','vatNoLabel'));
    }

    public function generateUniqueEmail($baseEmail)
    {
        $emailParts = explode('@', $baseEmail);
        $baseName = $emailParts[0];
        $domain = $emailParts[1];

        $index = 1;
        $newEmail = $baseEmail;

        while (User::where('email', $newEmail)->exists()) {
            $newEmail = $baseName . $index . '@' . $domain;
            $index++;
        }

        return $newEmail;
    }

    public function store(CreateClientRequest $request): RedirectResponse
    {   
        $input = $request->all();
        try {
            $validated = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'address' => 'nullable|string',
                'note' => 'nullable|string',
                'company_name' => 'nullable|string|max:255',
                 ]);

            // Generate a unique email
            $baseEmail = '1@gmail.com';
            $uniqueEmail = $this->generateUniqueEmail($baseEmail);

            // Create a new user
            $user = User::create([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $uniqueEmail,
                 ]);

            // Create a new client associated with the user
            $client = Client::create([
                'user_id' => $user->id,
                'address' => $validated['address'],
                'note' => $validated['note'],
                'company_name' => $validated['company_name'],
            ]);

            Flash::success(__('messages.flash.client_created_successfully'));
        } catch (Exception $exception) {
            Flash::error($exception->getMessage());
            return redirect()->route('clients.create')->withInput();
        }

        return redirect()->route('clients.index');
    }

    /**
     * @return Application|Factory|View
     */
    public function show(Client $client, Request $request): \Illuminate\View\View
    {
        $client->load('user.media', 'invoices.payments');
        $activeTab = $request->get('Active', 'overview');
        $data = $this->clientRepository->getData();
        $vatNoLabel = getVatNoLabel();

        return view('clients.show', compact('client', 'activeTab','vatNoLabel'));
    }

    /**
     * @return Application|Factory|View
     */
    public function edit(Client $client)
    {
        $data = $this->clientRepository->getData();
        $countries = $data['countries'];
        $vatNoLabel = getVatNoLabel();
        $client->load('user.media');

        return view('clients.edit', compact('client', 'countries','vatNoLabel'));
    }

    public function update(Client $client, UpdateClientRequest $request)
    {
        $input = $request->all();
        $client->load('user');

        try {
            $validated = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'address' => 'nullable|string',
                'note' => 'nullable|string',
                'company_name' => 'nullable|string|max:255',
                'email' => 'required|string|email|max:255', // Assuming email can be updated
                'password' => 'nullable|string|min:8|confirmed', // Assuming password is optional on update
            ]);

            // Check if email has changed
            if ($validated['email'] !== $client->user->email) {
                $uniqueEmail = $this->generateUniqueEmail($validated['email']);
                $client->user->email = $uniqueEmail;
            }

            // Update user details
            $client->user->first_name = $validated['first_name'];
            $client->user->last_name = $validated['last_name'];
            if (!empty($validated['password'])) {
                $client->user->password = bcrypt($validated['password']);
            }
            $client->user->save();

            // Update client details
            $client->address = $validated['address'];
            $client->note = $validated['note'];
            $client->company_name = $validated['company_name'];
            $client->save();

            Flash::success(__('messages.flash.client_updated_successfully'));
        } catch (Exception $exception) {
            Flash::error($exception->getMessage());

            return redirect()->back()->withInput();
        }

        return redirect()->route('clients.index');
    }

    public function destroy(Client $client, Request $request): JsonResponse
    {
        $check = $request->get('clientWithInvoices');
        $invoiceModels = [
            Invoice::class,
        ];
        $result = canDelete($invoiceModels, 'client_id', $client->id);
        if ($check && $result) {
            return $this->sendError(__('messages.flash.client_cant_deleted'));
        }
        $client->user()->delete();
        $client->invoices()->delete();
        $client->delete();

        return $this->sendSuccess(__('messages.flash.client_deleted_successfully'));
    }

    public function getStates(Request $request): mixed
    {
        $countryId = $request->get('countryId');
        $states = getStates($countryId);

        return $this->sendResponse($states,__('messages.flash.status_retrieved_successfully'));
    }

    /**
     * @return mixed
     */
    public function getCities(Request $request)
    {
        $stateId = $request->get('stateId');
        $cities = getCities($stateId);

        return $this->sendResponse($cities, __('messages.flash.cities_retrieved_successfully'));
    }
    public function exelExport(): Response
{
    ini_set('max_execution_time', 36000000);

    return Excel::download(new ClientsExport, 'Clients.xlsx');
}
     public function pdfExport()
    {
         // Retrieve the payment record by ID
        $clients = Client::all();
        
        // return view('payments.payment_pdf', compact("adminPayments"));

        // Load the view and pass the payment data to it
        $pdf = PDF::loadView('clients.clients_pdf_export', compact('clients'));

        // Return the PDF as a download with a proper filename
        return $pdf->stream("Clients data");
    }
}
