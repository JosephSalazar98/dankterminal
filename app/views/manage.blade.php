@php use Illuminate\Support\Str; @endphp


<!DOCTYPE html>
<html lang="en" x-data="{ showModal: false, editId: null, editDesc: '' }">

<head>
    <meta charset="UTF-8" />
    <title>Manage Memes</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/dark.css">
    @alpine
</head>

<body>

    <h2>Manage Memes</h2>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($memes as $meme)
                <tr>
                    <td>{{ $meme->id }}</td>
                    <td>{{ Str::limit($meme->description, 60) }}</td>
                    <td>
                        <button
                            @click="editId = {{ $meme->id }}; editDesc = '{{ addslashes($meme->description) }}'; showModal = true">Edit</button>

                        <form method="POST" action="/memes/delete" style="display:inline;"
                            onsubmit="return confirm('Delete this meme?')">
                            @csrf
                            <input type="hidden" name="id" value="{{ $meme->id }}">
                            <button style="color:red;">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <a href="/dashboard">Back to Upload</a>

    <!-- Modal Backdrop -->
    <div x-show="showModal" style="position: fixed; inset: 0; background: rgba(0,0,0,0.7); z-index: 50;" x-transition>

        <!-- Flex Wrapper to center modal -->
        <div style="display: flex; height: 100%; align-items: center; justify-content: center;">

            <!-- Actual Modal -->
            <div
                style="background: #222; padding: 2rem; border-radius: 8px;
                    width: 90%; max-width: 600px; box-shadow: 0 0 15px rgba(0,0,0,0.5);">
                <h3 style="margin-top: 0;">Edit Description</h3>
                <form method="POST" action="/memes/update-description">
                    @csrf
                    <input type="hidden" name="id" :value="editId">
                    <textarea name="description" x-model="editDesc" style="width: 100%; height: 120px; margin-bottom: 1rem;"></textarea>
                    <div style="display: flex; justify-content: flex-end; gap: 1rem;">
                        <button type="submit">Save</button>
                        <button type="button" @click="showModal = false">Cancel</button>
                    </div>
                </form>
            </div>

        </div>
    </div>



</body>

</html>
