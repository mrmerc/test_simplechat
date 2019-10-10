<template>
	<div id="chat-container" class="container border p-2">
		<div id="feed">
			<ul class="list-group">
				<li v-for="message in messages" :key="message.id" class="list-group-item">
					{{ getDateString(message.date) }} {{ message.name }}: {{ message.text }}
				</li>
				<p v-if="!messages.length">Сообщений нет</p>
				<p v-else>Начало истории</p>
			</ul>
		</div>
		<div id="composer">
			<textarea v-model="my_message" placeholder="Сообщение" rows="1"></textarea>
			<input @click.prevent="sendMessage" class="btn btn-success m-1" type="button" value="Отправить">
		</div>
	</div>
</template>

<script>
export default {
	props: {
		user: {
			required: true,
			type: Object,
		}
	},

	data() {
		return {
			messages: [],
			my_message: '',
		}
	},

	mounted() {
		this.getHistory();

		this.channel
			.joining(user => {
				this.messages.unshift({
					text: 'Подключился',
					date: Date.now(),
					name: user.name,
					from: user.id,
				});
			})
			.listen('MessageSent', ({ data }) => {
				this.messages.unshift(JSON.parse(data));
			});
	},

	computed: {
		channel() {
			return window.Echo.join('chat');
		}
	},

	methods: {
		sendMessage() {
			if (!this.my_message.length) return;

			const msg = {
				text: this.my_message,
				date: Date.now(),
				name: this.user.name,
				from: this.user.id,
			}
			
			axios.post('api/message', JSON.stringify(msg), {
				params: {
					api_token: this.user.api_token,
				}
			})
			.then(() => {
				this.messages.unshift(msg);
				this.my_message = '';
			})
			.catch(({ response }) => console.log(response));
		},

		getHistory() {
			axios.get('api/message/history', {
				params: {
					api_token: this.user.api_token,
				}
			})
			.then(({ data }) => {
				this.messages = data.history;
			})
			.catch(({ response }) => console.log(response));
		},

		getDateString(timestamp) {
			const date = new Date(parseInt(timestamp));

			const day = (date.getDate() < 10) ? `0${date.getDate()}` : date.getDate();
			const month = (date.getMonth() + 1 < 10) ? `0${(date.getMonth() + 1)}` : date.getMonth() + 1;
			const hours = (date.getHours() < 10) ? `0${date.getHours()}` : date.getHours();
			const minutes = (date.getMinutes() < 10) ? `0${date.getMinutes()}` : date.getMinutes();

			return `${ day }.${ month }.${ date.getFullYear() } ${ hours }:${ minutes }`;
		},
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