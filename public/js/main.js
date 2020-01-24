var vm = new Vue({
  el: '#board-list-table',
  methods : {
    inputNewNameBoard: function(boardId) {
      let elementButton = document.getElementById('inputNameBoard'+boardId)
      elementButton.removeAttribute('readonly')
    },

    changeNameBoard: function(id) {
      let newName = document.getElementById('inputNameBoard'+id).value
      if (newName == '') {
        axios.post(`/board/delete/${id}`)
        .then(response => {
          console.log(response)
          if (response.data == 'success') {
            alert('delete board success');
            location.reload();
          } if(response.data == 'error') {
            alert('delete board failed, because your not the creator.')
          }
        })
      } else {
        axios.post(`/board/update-name/${id}/${newName}`) 
        .then(response => {
          alert('update board success');
          location.reload();
        })
      }
    }

  }

})

new Vue({
  el: '.list-members-table',
  methods: {
    deleteMember: function(userId, boardId) {
      if (confirm('Delete?')) {
        axios.post(`/member/delete/${userId}/${boardId}`)
        .then(response => {
          if (response.data == 'success') {
            alert('remove member success');
            location.reload()
          } if (response.data == 'error') {
            alert('cannot delete creator from member list');
          }
        })
      }
    }
  }
})

new Vue({
  el: '.list-table',
  methods: {
    removeReadonlyAttr: function(listId) {
      let elementButton = document.getElementById('list-input'+listId)
      elementButton.removeAttribute('readonly')
    },
    updateList: function(listId, boardId, order) {
      let newListName = document.getElementById('list-input'+listId).value

      if (newListName == '') {
        axios.post('/board/delete-list', {
          list_id : listId
        })
        .then(response => {
          alert('delete list success.')
          location.reload();
        })
      } else {
        axios.post('/board/update-list', {
          list_id : listId,
          board_id : boardId,
          order: order,
          new_name: newListName
        })
        .then(response => {
          alert('update list success.')
          location.reload();
          })
      }

    }
  }
})

new Vue({
  el: '.cards-table',
  methods: {
    removeReadonlyAttr: function(cardId) {
      let elementButton = document.getElementById('card-input'+cardId)
      elementButton.removeAttribute('readonly')
    },
    updateCard: function(listId, cardId) {
      let newName = document.getElementById('card-input'+cardId).value
      if (newName == '') {
        axios.post('/list/delete', {
          'list_id': listId,
          'card_id': cardId,
          'name': newName
        }).then(response => {
          alert('delete card success!')
          console.log(response)
          location.reload()
        })
      } else {
        axios.post('/list/update', {
          'list_id': listId,
          'card_id': cardId,
          'name': newName
        }).then(response => {
          alert('update card success!')
          console.log(response)
          location.reload()
        })
      }
    }
  }
})