<template>
	<div id="chat-container" class="container border p-2">
		<div id="feed">
			<ul class="list-group">
				<li class="list-group-item">12:40 Вася: Привет</li>
				<li class="list-group-item">12:41 Петя: Приветик</li>
				<li class="list-group-item">12:41 Петя: Приветик</li>
				<li class="list-group-item">12:41 Петя: Приветик</li>
				<li class="list-group-item">12:41 Петя: Приветик</li>
				<li class="list-group-item">12:41 Петя: Приветик</li>
				<li class="list-group-item">12:41 Петя: Приветик</li>
				<li class="list-group-item">12:41 Петя: Приветик</li>
				<li class="list-group-item">12:41 Петя: Приветик</li>
			</ul>
		</div>
		<div id="composer">
			<textarea placeholder="Сообщение" rows="1"></textarea>
			<input class="btn btn-success m-1" type="button" value="Отправить">
		</div>
	</div>
</template>

<script>
export default {
	data() {
		return {
			messages: [],
			my_message: '',
		}
	},

	mounted() {

	},

	computed: {

	},

	methods: {

	},
}
</script>

<style lang="scss" scoped>
	.container {

		#feed {
			height: 300px;
			overflow-x: hidden;
			overflow-y: scroll;

			ul {
				list-style: none;
				display: flex;
				flex-direction: column-reverse;
			}
		}

		#composer {
			height: 50px;
			display: flex;
			align-items: center;

			textarea {
				width: 80%;
				resize: none;
				padding-left: 8px;
			}

			input {
				width: 20%;
			}
		}
	}
</style>