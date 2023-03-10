<style>
    .max-height-70 {
        max-height: 70px;
    }
</style>
<footer class="text-center text-white fixed-bottom" style="background-color: black;">

    <!-- Copyright -->
    <div class="container-fluid max-height-70">
        <a href="https://zap-hosting.com/badger" target="_blank">
            <div class="row max-height-70">
                <div class="col-4 max-height-70" style="background-color: #57BC54;">
                </div>
                <div class="col-4 px-0 max-height-70 d-flex">
                    <div class="flex-grow-1" style="background-color: #57BC54"></div>
                    <img src="/img/badger-partnership-zap-banner.png" style="height: 100%" />
                </div>
                <div class="col-4 max-height-70">
                </div>
            </div>
        </a>
    </div>
    <!-- Copyright -->
</footer>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.1/js/bootstrap.min.js" integrity="sha512-EKWWs1ZcA2ZY9lbLISPz8aGR2+L7JVYqBAYTq5AXgBkSjRSuQEGqWx8R1zAX16KdXPaCjOCaKE8MCpU0wcHlHA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js" integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap5-toggle@5.0.4/js/bootstrap5-toggle.jquery.min.js"></script>
<script src="/js/jquery.gridster.min.js"></script>
<script src="/js/jquery.gridster.with-extras.min.js"></script>
<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/fixedheader/3.2.0/js/dataTables.fixedHeader.min.js"></script>
@if($data['captcha'])
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script>
        let actuallySubmit = false;
        $('#form').submit(function (event) {
            if (!actuallySubmit) {
                event.preventDefault();
                grecaptcha.reset();
                grecaptcha.execute();
            }
        });
        function onSubmit() {
            actuallySubmit = true;
            $('#form').submit();
        }
    </script>
@endif
