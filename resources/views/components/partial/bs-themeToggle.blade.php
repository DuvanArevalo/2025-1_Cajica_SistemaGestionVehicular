{{-- Botón toggle de tema --}}
<button id="themeToggle" class="btn btn-outline-secondary" type="button" aria-label="Cambiar tema">
  @auth
    <label>Cambiar tema</label>
  @endauth
  <i id="sunIcon" class="bi bi-sun"></i>
  <i id="moonIcon" class="bi bi-moon"></i>
</button>

@push('scripts')
  <script src="{{ asset('js/bs-themeToggle.js') }}"></script>
@endpush