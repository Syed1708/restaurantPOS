@extends('tyro-dashboard::layouts.app')

@section('title', 'SaaS Live Analytics')

@section('breadcrumb')
<span>Live Analytics</span>
@endsection

@section('content')
<div class="page-header">
    <div class="page-header-row">
        <div>
            <h1 class="page-title">📊 Live Sales Analytics (Today)</h1>
            <p class="page-description">Real-time financial summaries synced from your fast-food terminals.</p>
        </div>
    </div>
</div>

<!-- 1. TOP CARDS: Core Totals (HT/TVA/TTC) -->
<div class="stats-grid" style="margin-bottom: 2rem;">
    <!-- Card 1: Total TTC -->
    <div class="stat-card">
        <div class="stat-icon stat-icon-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <div class="stat-content">
            <div class="stat-label">Chiffre d'Affaires (TTC)</div>
            <div class="stat-value" style="color: var(--primary);">{{ number_format($totals->total_ttc, 2, ',', ' ') }} €</div>
        </div>
    </div>

    <!-- Card 2: Total HT -->
    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(100, 116, 139, 0.1); color: rgb(100, 116, 139);">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 00-2 2z" />
            </svg>
        </div>
        <div class="stat-content">
            <div class="stat-label">Chiffre d'Affaires (HT)</div>
            <div class="stat-value" style="color: rgb(100, 116, 139);">{{ number_format($totals->total_ht, 2, ',', ' ') }} €</div>
        </div>
    </div>

    <!-- Card 3: Total TVA -->
    <div class="stat-card">
        <div class="stat-icon stat-icon-success">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
            </svg>
        </div>
        <div class="stat-content">
            <div class="stat-label">TVA Total Collectée</div>
            <div class="stat-value" style="color: var(--success);">{{ number_format($totals->total_tva, 2, ',', ' ') }} €</div>
        </div>
    </div>
</div>

<!-- 2. SPLIT ROW: Payment Methods & VAT Breakdown -->
<div class="grid-2" style="margin-bottom: 2rem;">
    <!-- Card Left: Payments -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">💳 Modes de Règlement</h3>
        </div>
        <div class="card-body">
            @if($payments->isEmpty())
                <p style="color: var(--muted-foreground); text-align: center; padding: 1.5rem 0;">Aucune transaction réglée aujourd'hui.</p>
            @else
                @foreach($payments as $payment)
                    <div style="display: flex; justify-content: space-between; align-items: center; background: var(--muted); padding: 0.75rem 1rem; border-radius: 8px; margin-bottom: 0.75rem;">
                        <span style="font-weight: 600; text-transform: capitalize;">
                            @if($payment->method === 'cash') 💵 Espèces @elseif($payment->method === 'card') 💳 Carte @else 🎟️ Ticket Resto @endif
                        </span>
                        <strong style="font-size: 1.1rem;">{{ number_format($payment->total_amount, 2, ',', ' ') }} €</strong>
                    </div>
                @endforeach
            @endif
        </div>
    </div>

    <!-- Card Right: VAT Bracket Breakdown -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">⚖️ Répartition de la TVA (French Brackets)</h3>
        </div>
        <div class="card-body">
            @if($vatBreakdown->isEmpty())
                <p style="color: var(--muted-foreground); text-align: center; padding: 1.5rem 0;">Aucun produit vendu aujourd'hui.</p>
            @else
                @foreach($vatBreakdown as $bracket)
                    <div style="display: flex; justify-content: space-between; align-items: center; border-left: 4px solid var(--success); background: var(--muted); padding: 0.75rem 1rem; border-radius: 0 8px 8px 0; margin-bottom: 0.75rem; padding-left: 1.25rem;">
                        <div>
                            <strong style="font-size: 1rem; display: block; color: var(--foreground);">Taux {{ number_format($bracket->vat_rate, 1, ',', ' ') }}%</strong>
                            <span style="font-size: 0.75rem; color: var(--muted-foreground);">Chiffre TTC: {{ number_format($bracket->total_ttc, 2, ',', ' ') }} €</span>
                        </div>
                        <strong style="color: var(--success); font-size: 1.1rem;">+{{ number_format($bracket->collected_vat, 2, ',', ' ') }} €</strong>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>

<!-- 3. BOTTOM ROW: Recent Transactions -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">📄 10 Dernières Transactions Synchronisées</h3>
    </div>
    <div class="card-body" style="padding: 0;">
        @if($recentOrders->isEmpty())
            <p style="color: var(--muted-foreground); text-align: center; padding: 1.5rem 0;">Aucune transaction enregistrée.</p>
        @else
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Ticket</th>
                            <th>Boutique</th>
                            <th>Date / Heure</th>
                            <th>Montant (TTC)</th>
                            <th style="text-align: right;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentOrders as $order)
                        <tr>
                            <td style="font-weight: bold;">
                                @if($order->total_incl_vat < 0)
                                    <span style="color: var(--destructive);">❌ Avoir #{{ $order->sequence_number }}</span>
                                @else
                                    <span>Ticket #{{ $order->sequence_number }}</span>
                                @endif
                            </td>
                            <td>{{ $order->store->name ?? 'N/A' }}</td>
                            <td>{{ $order->completed_at->format('d/m/Y H:i') }}</td>
                            <td style="font-weight: bold; {{ $order->total_incl_vat < 0 ? 'color: var(--destructive);' : '' }}">
                                {{ number_format($order->total_incl_vat, 2, ',', ' ') }} €
                            </td>
                            <td style="text-align: right;">
                                <span class="badge badge-success">Synchronisé ☁️</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection