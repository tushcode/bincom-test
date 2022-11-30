<?php
    $lga = $this->db->fetchAll('SELECT * FROM lga');
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="author" content="Solomon Okafor" />
        <meta name="generator" content="Bincom Test 1.0.0" />
        <title>Question 2 Page - Bincom Test</title>

        <!-- CSS only -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">

        <style>
            .bd-placeholder-img {
                font-size: 1.125rem;
                text-anchor: middle;
                -webkit-user-select: none;
                -moz-user-select: none;
                user-select: none;
            }

            @media (min-width: 768px) {
                .bd-placeholder-img-lg {
                    font-size: 3.5rem;
                }
            }

            .b-example-divider {
                height: 3rem;
                background-color: rgba(0, 0, 0, 0.1);
                border: solid rgba(0, 0, 0, 0.15);
                border-width: 1px 0;
                box-shadow: inset 0 0.5em 1.5em rgba(0, 0, 0, 0.1), inset 0 0.125em 0.5em rgba(0, 0, 0, 0.15);
            }

            .b-example-vr {
                flex-shrink: 0;
                width: 1.5rem;
                height: 100vh;
            }

            .bi {
                vertical-align: -0.125em;
                fill: currentColor;
            }

            .nav-scroller {
                position: relative;
                z-index: 2;
                height: 2.75rem;
                overflow-y: hidden;
            }

            .nav-scroller .nav {
                display: flex;
                flex-wrap: nowrap;
                padding-bottom: 1rem;
                margin-top: -1px;
                overflow-x: auto;
                text-align: center;
                white-space: nowrap;
                -webkit-overflow-scrolling: touch;
            }
        </style>

    </head>
    <body>
        <div class="col-lg-8 mx-auto p-4 py-md-5">
            <header class="d-flex align-items-center pb-3 mb-5 border-bottom">
                <a href="./" class="d-flex align-items-center text-dark text-decoration-none">
                    <span class="fs-4">Question 2 Bincom Test</span>
                </a>
            </header>

            <main>
                <h1>Local Government Polling Unit Results</h1>
                <p class="fs-5 col-md-8">
                    Here are the results of all the polling places for any local government added together.
                </p>
                
                <div class="row">
                    <div class="col-lg-6">
                        <select class="form-select form-select-lg mb-3" id="lga_polling" required>
                            <option value="" selected>Select Local Govt.</option>
                            <?php
                            foreach($lga as $val){ ?>
                                <option value="<?= $val->lga_id; ?>"><?= $val->lga_name; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-lg-6">
                        <input class="form-control form-control-lg" id="lga-total" readonly disabled type="text" value="Total Results: 0">
                    </div>
                </div>

                <div class="mb-5">
                    <a href="question-3" class="btn btn-primary btn-lg px-4">Goto Question 3</a>
                </div>

            </main>
            <footer class="pt-5 my-5 text-muted border-top">
                Bincom Test &copy; Copyright <?= date('Y'); ?>
            </footer>
        </div>

        <!-- JavaScript Bundle with Popper -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        
        <!-- Tushcode JS -->
        <script src="<?= URLROOT . '/assets/js/tush.min.js'; ?>"></script>
    </body>
</html>
