{{--
  Modal konfirmasi umum untuk halaman admin.
  Pakai di dalam elemen Alpine yang punya state `ask` (null = tertutup):

    <div x-data="{ ask: null }"> ... <x-admin.confirm /> </div>

  Isi `ask`:
    { action, method: 'PATCH'|'DELETE'|'POST', value: 0|1|null,
      tone: 'info'|'ok'|'warn'|'danger', icon: 'crown'|'shield'|'trash'|'check'|'x'|'alert',
      title, text, button,
      form?: id form lain yang akan disubmit saat dikonfirmasi (untuk form yang punya input sendiri) }
--}}
<div class="adm-modal" x-cloak x-show="ask" x-transition.opacity
  @keydown.escape.window="ask = null" @click.self="ask = null">
  <template x-if="ask">
    <form method="POST" :action="ask.action" class="adm-reject adm-modal-card adm-confirm-card" :class="'is-' + ask.tone"
      @submit="if (ask.form) { $event.preventDefault(); document.getElementById(ask.form).requestSubmit(); }">
      @csrf
      <input type="hidden" name="_method" :value="ask.method">
      <input type="hidden" name="value" :value="ask.value" :disabled="ask.value === null || ask.value === undefined">

      <div class="adm-confirm-icon" :class="'is-' + ask.tone">
        <template x-if="ask.icon === 'crown'"><x-admin.icon name="crown" /></template>
        <template x-if="ask.icon === 'shield'"><x-admin.icon name="shield" /></template>
        <template x-if="ask.icon === 'trash'"><x-admin.icon name="trash" /></template>
        <template x-if="ask.icon === 'check'"><x-admin.icon name="check" /></template>
        <template x-if="ask.icon === 'x'"><x-admin.icon name="x" /></template>
        <template x-if="ask.icon === 'alert'"><x-admin.icon name="alert" /></template>
      </div>

      <h2 x-text="ask.title"></h2>
      <p class="adm-modal-text" x-text="ask.text"></p>

      <div class="adm-modal-actions">
        <button type="button" class="adm-btn adm-btn-ghost" @click="ask = null">Batal</button>
        <button type="submit" class="adm-btn"
          :class="{ 'adm-btn-no': ask.tone === 'danger', 'adm-btn-ok': ask.tone === 'ok' }"
          x-text="ask.button"></button>
      </div>
    </form>
  </template>
</div>