<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        .color-input {
            width: 100px;
            height: 30px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        .color-preview {
            width: 30px;
            height: 30px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-left: 10px;
        }
    </style>
</head>
<body>
        <h1>Color Preferences</h1>
        <form action="{{ route('color.save') }}" method="POST">
            @csrf
            <div>
                <label for="sidebar_color">Sidebar Color:</label>
                <input type="color" id="sidebar_color" name="sidebar_color" class="color-input" 
                    value="{{ $companies_colors->sidebar_color ?? '' }}" pattern="^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$">
                
            </div>
            <div>
                <label for="button_color">Button Color:</label>
                <input type="color" id="button_color" name="button_color" class="color-input" 
                    value="{{ $companies_colors->button_color ?? '' }}" pattern="^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$">
                
            </div>
            <button type="submit">Save</button>
        </form>
</body>
</html>
