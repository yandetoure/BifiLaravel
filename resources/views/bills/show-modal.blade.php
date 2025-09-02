<div class="row">
    <div class="col-md-6">
        <h6 class="text-primary mb-3">
            <i class="fas fa-file-invoice me-2"></i>Informations de la facture
        </h6>
        <table class="table table-borderless">
            <tr>
                <td class="fw-bold">Numéro de facture :</td>
                <td>#{{ $bill->bill_number }}</td>
            </tr>
            <tr>
                <td class="fw-bold">Numéro client :</td>
                <td>{{ $bill->client_number }}</td>
            </tr>
            <tr>
                <td class="fw-bold">Montant TTC :</td>
                <td class="fw-bold text-success">{{ number_format($bill->amount, 0, ',', ' ') }} FCFA</td>
            </tr>
            <tr>
                <td class="fw-bold">Entreprise :</td>
                <td>
                    <div class="d-flex align-items-center">
                        @if($bill->company->logo)
                            <img src="{{ asset('storage/' . $bill->company->logo) }}" 
                                 alt="{{ $bill->company->name }}" 
                                 class="rounded me-2" width="24" height="24">
                        @endif
                        {{ $bill->company->name }}
                    </div>
                </td>
            </tr>
            <tr>
                <td class="fw-bold">Statut :</td>
                <td>
                    @switch($bill->status)
                        @case('pending')
                            <span class="badge bg-warning text-dark">
                                <i class="fas fa-clock me-1"></i>En attente
                            </span>
                            @break
                        @case('confirmed')
                            <span class="badge bg-success">
                                <i class="fas fa-check-circle me-1"></i>Confirmée
                            </span>
                            @break
                        @case('paid')
                            <span class="badge bg-info">
                                <i class="fas fa-credit-card me-1"></i>Payée
                            </span>
                            @break
                        @case('cancelled')
                            <span class="badge bg-danger">
                                <i class="fas fa-times-circle me-1"></i>Annulée
                            </span>
                            @break
                    @endswitch
                </td>
            </tr>
            <tr>
                <td class="fw-bold">Date de soumission :</td>
                <td>{{ $bill->created_at->format('d/m/Y à H:i') }}</td>
            </tr>
        </table>
    </div>
    
    <div class="col-md-6">
        <h6 class="text-primary mb-3">
            <i class="fas fa-user me-2"></i>Informations du client
        </h6>
        @if($bill->user)
            <table class="table table-borderless">
                <tr>
                    <td class="fw-bold">Nom :</td>
                    <td>{{ $bill->user->name }}</td>
                </tr>
                <tr>
                    <td class="fw-bold">Email :</td>
                    <td>{{ $bill->user->email }}</td>
                </tr>
                <tr>
                    <td class="fw-bold">Type de compte :</td>
                    <td>
                        <span class="badge bg-secondary">Client inscrit</span>
                    </td>
                </tr>
            </table>
        @elseif($bill->client_name)
            <table class="table table-borderless">
                <tr>
                    <td class="fw-bold">Nom :</td>
                    <td>{{ $bill->client_name }}</td>
                </tr>
                <tr>
                    <td class="fw-bold">Numéro client :</td>
                    <td>{{ $bill->client_number }}</td>
                </tr>
                <tr>
                    <td class="fw-bold">Type de compte :</td>
                    <td>
                        <span class="badge bg-info">Client externe</span>
                    </td>
                </tr>
            </table>
        @else
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                <strong>Client anonyme</strong><br>
                Cette facture a été soumise sans création de compte.
            </div>
        @endif
        
        @if($bill->cancellation_message)
            <h6 class="text-danger mb-3 mt-4">
                <i class="fas fa-times-circle me-2"></i>Motif d'annulation
            </h6>
            <div class="alert alert-danger">
                {{ $bill->cancellation_message }}
            </div>
        @endif
    </div>
</div>

@if($bill->uploaded_file)
<hr>
<div class="row">
    <div class="col-12">
        <h6 class="text-primary mb-3">
            <i class="fas fa-paperclip me-2"></i>Fichier joint
        </h6>
        <div class="d-flex align-items-center">
            <i class="fas fa-file fa-2x text-muted me-3"></i>
            <div>
                <div class="fw-bold">Facture uploadée</div>
                <small class="text-muted">
                    Fichier joint par le client
                </small>
            </div>
            <div class="ms-auto">
                <a href="{{ asset('storage/' . $bill->uploaded_file) }}" 
                   target="_blank" class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-download me-1"></i>Télécharger
                </a>
            </div>
        </div>
    </div>
</div>
@endif

@if($bill->payments->count() > 0)
<hr>
<div class="row">
    <div class="col-12">
        <h6 class="text-primary mb-3">
            <i class="fas fa-credit-card me-2"></i>Historique des paiements
        </h6>
        <div class="table-responsive">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Méthode</th>
                        <th>Montant</th>
                        <th>Référence</th>
                        <th>Agent</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bill->payments as $payment)
                    <tr>
                        <td>{{ $payment->transaction_date->format('d/m/Y H:i') }}</td>
                        <td>{{ ucfirst($payment->payment_method) }}</td>
                        <td class="fw-bold">{{ number_format($payment->total, 0, ',', ' ') }} FCFA</td>
                        <td><code>{{ $payment->transaction_reference }}</code></td>
                        <td>{{ $payment->agent->name ?? 'N/A' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif 