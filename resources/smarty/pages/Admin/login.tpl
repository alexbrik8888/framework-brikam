<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход в панель управления</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: system-ui, -apple-system, sans-serif; }

        body {
            background: #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            color: #334155;
        }

        .login-card {
            background: #ffffff;
            width: 100%;
            max-width: 400px;
            padding: 40px 30px;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .login-header h1 {
            font-size: 24px;
            color: #0f172a;
            margin-bottom: 8px;
        }

        .login-header p {
            font-size: 14px;
            color: #64748b;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 14px;
            color: #475569;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.2s, box-shadow 0.2s;
            outline: none;
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
        }

        .alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
            padding: 12px;
            border-radius: 8px;
            font-size: 14px;
            margin-bottom: 20px;
        }

        .btn-submit {
            width: 100%;
            background: #2563eb;
            color: #ffffff;
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .btn-submit:hover {
            background: #1d4ed8;
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="login-header">
        <h1>Админ-панель</h1>
        <p>Введите данные для входа в систему</p>
    </div>

    {* Вывод сообщения об ошибке при авторизации *}
    {if isset($error_message)}
        <div class="alert-error">
            {$error_message}
        </div>
    {/if}

    <form action="/admin/login" method="POST">

        <div class="form-group">
            <label for="username">Логин или E-mail</label>
            <input type="text" id="email" name="email" value="{$email|default:''}" required autofocus autocomplete="username">
        </div>

        <div class="form-group">
            <label for="password">Пароль</label>
            <input type="password" id="password" name="password" required autocomplete="current-password">
        </div>

        <button type="submit" class="btn-submit">Войти</button>
    </form>
</div>

</body>
</html>