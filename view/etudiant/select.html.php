<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Segoe UI", sans-serif
        }

        :root {
            --brown: #4a2e16;
            --orange: #ff9d33;
            --blue: #2d73d2;
            --green: #1e7a37;
            --red: #c03c30;
            --gray: #666;
            --overlay: rgba(0, 0, 0, .55);
            --glass: rgba(255, 255, 255, .96);
            --radius: 22px;
            --shadow: 0 12px 28px rgba(0, 0, 0, .18)
        }

        html,
        body {
            height: 100%
        }

        body {
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f5f6f8
        }

        .open-overlay {
            padding: 15px 28px;
            border: none;
            border-radius: var(--radius);
            background: linear-gradient(135deg, var(--brown), var(--orange));
            color: #fff;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: transform .25s
        }

        .open-overlay:hover {
            transform: translateY(-3px)
        }

        .overlay {
            position: fixed;
            inset: 0;
            background: var(--overlay);
            backdrop-filter: blur(12px);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            pointer-events: none;
            transition: opacity .45s
        }

        .overlay.show {
            opacity: 1;
            pointer-events: auto
        }

        .modal {
            background: var(--glass);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            padding: 34px 40px;
            transform: scale(.9);
            opacity: 0;
            transition: transform .45s, opacity .45s;
            width: fit-content;
            max-width: 96vw;
            max-height: 88vh;
            display: flex;
            flex-direction: column;
            position: relative
        }

        .overlay.show .modal {
            transform: scale(1);
            opacity: 1
        }

        .modal header {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 24px
        }

        .modal h2 {
            font-size: 1.55rem;
            font-weight: 800;
            color: var(--brown);
            display: flex;
            align-items: center;
            gap: 10px
        }

        .modal h2 i {
            animation: float 4s ease-in-out infinite;
            transition: transform .3s
        }

        .modal h2:hover i {
            transform: rotate(10deg) scale(1.1)
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0)
            }

            50% {
                transform: translateY(-4px)
            }
        }

        .modal p {
            font-size: .9rem;
            color: var(--gray);
            text-align: center;
            max-width: 520px;
            margin-top: 10px
        }

        .close {
            position: absolute;
            right: 16px;
            top: 16px;
            width: 40px;
            height: 40px;
            border: none;
            border-radius: 50%;
            background: #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            color: var(--brown);
            cursor: pointer;
            transition: background .25s, transform .25s
        }

        .close:hover {
            background: #e5e5e5;
            transform: rotate(90deg)
        }

        .search-wrap {
            position: relative;
            margin: auto;
            width: 100%;
            max-width: 540px
        }

        .search-wrap input {
            width: 100%;
            padding: 14px 58px 14px 52px;
            border: 2px solid #ddd;
            border-radius: var(--radius);
            font-size: 1rem;
            background: #fafafa;
            transition: border .25s, box-shadow .25s
        }

        .search-wrap input:focus {
            border-color: var(--orange);
            box-shadow: 0 0 0 3px rgba(255, 157, 51, .25);
            outline: none
        }

        .search-wrap i {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--brown)
        }

        .search-btn {
            position: absolute;
            right: 0;
            top: 0;
            height: 100%;
            width: 54px;
            border: none;
            border-radius: 0 var(--radius) var(--radius) 0;
            background: var(--orange);
            color: #fff;
            font-size: 1.05rem;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: transform .25s
        }

        .search-btn:hover {
            transform: translateY(-2px)
        }

        .content-area {
            flex: 1;
            overflow: auto;
            margin-top: 26px
        }

        .waiting {
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: var(--gray);
            gap: 14px;
            font-size: .94rem;
            animation: pulse 2.8s infinite
        }

        .waiting i {
            font-size: 1.9rem
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: .5
            }

            50% {
                opacity: 1
            }
        }

        .results {
            display: none
        }

        .table-box {
            overflow-x: auto
        }

        .results table {
            width: 100%;
            table-layout: auto;
            border-collapse: separate;
            border-spacing: 0;
            font-size: .92rem
        }

        .results th,
        .results td {
            padding: 14px 16px;
            text-align: left;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis
        }

        .results thead {
            background: #f0f0f0;
            position: sticky;
            top: 0;
            z-index: 1;
            box-shadow: 0 2px 6px rgba(0, 0, 0, .06)
        }

        .results tbody tr {
            opacity: 0;
            transform: translateY(14px);
            animation: rowIn .5s forwards
        }

        .results tbody tr:hover {
            background: rgba(255, 157, 51, .1);
            transform: translateY(-2px) !important;
            box-shadow: 0 4px 10px rgba(0, 0, 0, .08)
        }

        @keyframes rowIn {
            to {
                opacity: 1;
                transform: translateY(0)
            }
        }

        .index {
            width: 50px;
            text-align: right;
            font-weight: 700;
            color: var(--brown)
        }

        .matricule {
            font-family: monospace;
            color: #555;
            min-width: 120px
        }

        .fullname {
            display: flex;
            align-items: center;
            max-width: 320px
        }

        .name-text {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap
        }

        .avatar {
            flex: 0 0 34px;
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: var(--orange);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .8rem;
            font-weight: 700;
            margin-right: 10px;
            transition: box-shadow .3s
        }

        .results tbody tr:hover .avatar {
            box-shadow: 0 0 0 3px rgba(255, 157, 51, .25)
        }

        .badge {
            padding: 5px 12px;
            border-radius: 14px;
            font-size: .78rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 4px
        }

        .pink {
            background: #ffd8ec;
            color: #c53e84
        }

        .blue {
            background: #d9e6ff;
            color: var(--blue)
        }

        .green {
            background: #d6fadf;
            color: var(--green)
        }

        .red {
            background: #fbd5d3;
            color: var(--red)
        }

        .select-btn {
            border: none;
            border-radius: var(--radius);
            padding: 9px 22px;
            font-size: .85rem;
            font-weight: 700;
            cursor: pointer;
            transition: transform .2s, box-shadow .2s
        }

        .select-btn:hover:not(.disabled) {
            transform: translateY(-2px);
            box-shadow: 0 3px 8px rgba(0, 0, 0, .14)
        }

        .active {
            background: var(--blue);
            color: #fff
        }

        .disabled {
            background: #e0e0e0;
            color: #999;
            cursor: not-allowed
        }
    </style>
</head>

<body><button class="open-overlay" onclick="openOverlay()">Ouvrir la sélection</button>
    <div class="overlay" id="overlay">
        <div class="modal">
            <header>
                <h2><i class="fa-solid fa-user-graduate"></i>Sélectionner un étudiant</h2>
                <p>Recherchez puis sélectionnez un étudiant non inscrit cette année pour poursuivre.</p>
            </header><button class="close" onclick="closeOverlay()"><i class="fa-solid fa-xmark"></i></button>
            <div class="search-wrap"><i class="fa-solid fa-magnifying-glass"></i><input id="search"
                    placeholder="Recherche par matricule, nom ou prénom…"><button class="search-btn"
                    onclick="showList()"><i class="fa-solid fa-arrow-right"></i></button></div>
            <div class="content-area">
                <div class="waiting" id="wait"><i class="fa-solid fa-binoculars"></i>Veuillez effectuer une recherche
                    pour afficher la liste.</div>
                <div class="results" id="res">
                    <div class="table-box">
                        <table>
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Matricule</th>
                                    <th>Nom & Prénom(s)</th>
                                    <th>Sexe</th>
                                    <th>Inscrit&nbsp;?</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="index">1</td>
                                    <td class="matricule">2024-00213</td>
                                    <td>
                                        <div class="fullname"><span class="avatar">AS</span><span class="name-text">SOW
                                                Alice</span></div>
                                    </td>
                                    <td><span class="badge pink"><i class="fa-solid fa-venus"></i> F</span></td>
                                    <td><span class="badge green"><i class="fa-solid fa-check"></i> Oui</span></td>
                                    <td><button class="select-btn disabled"><i class="fa-solid fa-lock"></i>Déjà
                                            inscrit</button></td>
                                </tr>
                                <tr>
                                    <td class="index">2</td>
                                    <td class="matricule">2024-00257</td>
                                    <td>
                                        <div class="fullname"><span class="avatar">DM</span><span
                                                class="name-text">DIALLO Mohamed</span></div>
                                    </td>
                                    <td><span class="badge blue"><i class="fa-solid fa-mars"></i> M</span></td>
                                    <td><span class="badge red"><i class="fa-solid fa-xmark"></i> Non</span></td>
                                    <td><button class="select-btn active">Sélectionner</button></td>
                                </tr>
                                <tr>
                                    <td class="index">3</td>
                                    <td class="matricule">2024-00312</td>
                                    <td>
                                        <div class="fullname"><span class="avatar">NM</span><span
                                                class="name-text">NDIAYE Marie Louise</span></div>
                                    </td>
                                    <td><span class="badge pink"><i class="fa-solid fa-venus"></i> F</span></td>
                                    <td><span class="badge red"><i class="fa-solid fa-xmark"></i> Non</span></td>
                                    <td><button class="select-btn active">Sélectionner</button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>const ov = document.getElementById("overlay"), wait = document.getElementById("wait"), res = document.getElementById("res"); function openOverlay() { document.body.style.overflow = "hidden"; ov.classList.add("show"); wait.style.display = "flex"; res.style.display = "none" } function closeOverlay() { ov.classList.remove("show"); document.body.style.overflow = "auto" } function showList() { wait.style.display = "none"; res.style.display = "block"; document.querySelectorAll("tbody tr").forEach((r, i) => setTimeout(() => r.style.animationDelay = `${i * 80}ms`, 0)) }</script>
</body>

</html>