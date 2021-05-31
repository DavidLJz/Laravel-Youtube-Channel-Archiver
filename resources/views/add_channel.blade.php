<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Add channel</title>

	<style type="text/css">
		.channel-input { margin-bottom: 10px; }
	</style>
</head>
<body>
	<form id="channel-data-form" method="post" action="{{ route('yt.add_channels') }}">
		<div id="channel-input-list">
			<div class="channel-input">
				<label for="c-name-1">Channel name</label>
				<input type="text" id="c-name-1" name="names[]">

				<label for="c-id-1">Channel ID</label>
				<input type="text" id="c-id-1" name="ids[]">
			</div>

			<div class="channel-input">
				<label for="c-name-2">Channel name</label>
				<input type="text" id="c-name-2" name="names[]">

				<label for="c-id-2">Channel ID</label>
				<input type="text" id="c-id-2" name="ids[]">
			</div>
		</div>

		<button type="button" id="add-channel-input">Add more channels</button>

		<hr/>

		<button type="submit">Enviar</button>
	</form>

	<script>
		const addInput = document.getElementById('add-channel-input');

		addInput.addEventListener('click', function () {
			const list = document.getElementById('channel-input-list');

			const container = document.createElement('div');
			container.classList = 'channel-input';

			const inputs = [
				{ label : 'Channel Name', id : 'c-name-', name : 'names[]' },
				{ label : 'Channel ID', id : 'c-id-', name : 'ids[]' },
			];

			for (const i in inputs) {
				const label = document.createElement('label');
				const input = document.createElement('input');

				input.type = 'text';
				input.id = inputs[i].id + (i + 1);
				input.name = inputs[i].name;

				label.for = inputs[i].id + (i + 1);
				label.textContent = inputs[i].label;
			
				container.append(label);
				container.append(input);
			}

			list.append(container);
		});
	</script>
</body>
</html>