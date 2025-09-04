@extends('layouts.chat')

@section('content')
<style>
@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}

@keyframes flash {
    0% { background-color: rgba(59, 130, 246, 0.3); }
    50% { background-color: rgba(16, 185, 129, 0.5); }
    100% { background-color: rgba(59, 130, 246, 0.3); }
}

.flash-effect {
    animation: flash 0.5s ease-in-out;
}
</style>
<div style="height: 100vh; display: flex; flex-direction: column;">
    <!-- Header -->
    <div style="background: #1e40af; color: white; padding: 16px; display: flex; justify-content: space-between; align-items: center;">
        <div style="font-size: 1.5rem; font-weight: bold;">SkillsXchange</div>
        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" style="color: #ef4444; text-decoration: none;">Logout</a>
    </div>

    <!-- Active Trade Session Banner -->
    <div style="background: #1e40af; color: white; padding: 12px 16px; text-align: center;">
        <div style="font-size: 1.2rem; font-weight: bold; margin-bottom: 4px;">
            💛 Active Trade Session
        </div>
        <div style="font-size: 0.9rem;">
            Trading: {{ $trade->offeringSkill->name ?? 'Unknown' }} for {{ $trade->lookingSkill->name ?? 'Unknown' }}
        </div>
    </div>

    <!-- Main Content -->
    <div style="flex: 1; display: flex; overflow: hidden;">
        <!-- Session Chat (Left Panel) -->
        <div style="flex: 1; display: flex; flex-direction: column; border-right: 1px solid #e5e7eb;">
            <!-- Chat Header -->
            <div style="background: #1e40af; color: white; padding: 12px 16px; display: flex; justify-content: space-between; align-items: center;">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <span>💬</span>
                    <span>Session Chat</span>
                    <span id="new-message-indicator" style="display: none; background: #ef4444; color: white; padding: 2px 6px; border-radius: 10px; font-size: 0.7rem; animation: pulse 2s infinite;">NEW</span>
                </div>
                <div style="display: flex; gap: 12px;">
                    <button style="background: none; border: none; color: white; cursor: pointer; font-size: 1.2rem;">📷</button>
                    <button style="background: none; border: none; color: white; cursor: pointer; font-size: 1.2rem;">🎤</button>
                    <button style="background: none; border: none; color: white; cursor: pointer; font-size: 1.2rem;">⚠️</button>
                </div>
            </div>

            <!-- Chat Messages -->
            <div id="chat-messages" style="flex: 1; padding: 16px; overflow-y: auto; background: #f9fafb;">
                @foreach($messages as $message)
                    <div style="margin-bottom: 16px; display: flex; {{ $message->sender_id === Auth::id() ? 'justify-content: flex-end' : 'justify-content: flex-start' }};">
                        <div style="max-width: 70%; {{ $message->sender_id === Auth::id() ? 'background: #3b82f6; color: white;' : 'background: #e5e7eb; color: #374151;' }} padding: 12px; border-radius: 12px; position: relative;">
                            <div style="margin-bottom: 4px;">{{ $message->message }}</div>
                            <div style="font-size: 0.75rem; opacity: 0.8;">{{ $message->created_at->format('g:i A') }}</div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Message Input -->
            <div style="padding: 16px; background: white; border-top: 1px solid #e5e7eb;">
                <form id="message-form" style="display: flex; gap: 8px;">
                    <input type="text" id="message-input" placeholder="Type your message here..." 
                           style="flex: 1; padding: 12px; border: 1px solid #d1d5db; border-radius: 6px; outline: none;">
                    <button type="submit" style="background: #1e40af; color: white; padding: 12px 24px; border: none; border-radius: 6px; cursor: pointer; font-weight: 600;">Send</button>
                </form>

            </div>
        </div>

        <!-- Session Tasks (Right Sidebar) -->
        <div style="width: 350px; background: white; border-left: 1px solid #e5e7eb; display: flex; flex-direction: column;">
            <!-- Sidebar Header -->
            <div style="background: #f3f4f6; padding: 12px 16px; border-bottom: 1px solid #e5e7eb; display: flex; align-items: center; gap: 8px;">
                <span>☑️</span>
                <span style="font-weight: 600;">Session Tasks</span>
            </div>

            <!-- Tasks Content -->
            <div style="flex: 1; padding: 16px; overflow-y: auto;">
                <!-- Your Tasks -->
                <div style="margin-bottom: 24px;">
                    <h3 style="font-size: 1rem; font-weight: 600; margin-bottom: 12px; color: #374151;">Your Tasks</h3>
                    <div id="my-tasks">
                        @forelse($myTasks as $task)
                            <div class="task-item" data-task-id="{{ $task->id }}" style="margin-bottom: 12px; padding: 12px; background: #f9fafb; border-radius: 6px; border: 1px solid #e5e7eb;">
                                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                                    <input type="checkbox" {{ $task->completed ? 'checked' : '' }} 
                                           onchange="toggleTask({{ $task->id }})" 
                                           style="width: 16px; height: 16px;">
                                    <span style="font-weight: 500; {{ $task->completed ? 'text-decoration: line-through; color: #6b7280;' : '' }}">{{ $task->title }}</span>
                                </div>
                                @if($task->description)
                                    <div style="font-size: 0.875rem; color: #6b7280; margin-left: 24px;">{{ $task->description }}</div>
                                @endif
                            </div>
                        @empty
                            <div style="color: #6b7280; font-size: 0.875rem; text-align: center; padding: 16px;">No tasks assigned to you</div>
                        @endforelse
                    </div>
                    
                    <!-- Your Progress -->
                    <div style="margin-top: 16px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                            <span style="font-size: 0.875rem; color: #6b7280;">Progress</span>
                            <span style="font-size: 0.875rem; font-weight: 600;">{{ round($myProgress) }}%</span>
                        </div>
                        <div style="background: #e5e7eb; border-radius: 4px; height: 8px; overflow: hidden;">
                            <div style="background: #10b981; height: 100%; width: {{ $myProgress }}%; transition: width 0.3s ease;"></div>
                        </div>
                    </div>
                </div>

                <!-- Partner's Tasks -->
                <div style="margin-bottom: 24px;">
                    <h3 style="font-size: 1rem; font-weight: 600; margin-bottom: 12px; color: #374151;">{{ $partner->firstname }}'s Tasks</h3>
                    <div id="partner-tasks">
                        @forelse($partnerTasks as $task)
                            <div class="task-item" data-task-id="{{ $task->id }}" style="margin-bottom: 12px; padding: 12px; background: #f9fafb; border-radius: 6px; border: 1px solid #e5e7eb;">
                                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                                    <input type="checkbox" {{ $task->completed ? 'checked' : '' }} disabled style="width: 16px; height: 16px;">
                                    <span style="font-weight: 500; {{ $task->completed ? 'text-decoration: line-through; color: #6b7280;' : '' }}">{{ $task->title }}</span>
                                </div>
                                @if($task->description)
                                    <div style="font-size: 0.875rem; color: #6b7280; margin-left: 24px;">{{ $task->description }}</div>
                                @endif
                            </div>
                        @empty
                            <div style="color: #6b7280; font-size: 0.875rem; text-align: center; padding: 16px;">No tasks assigned to {{ $partner->firstname }}</div>
                        @endforelse
                    </div>
                    
                    <!-- Partner's Progress -->
                    <div style="margin-top: 16px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                            <span style="font-size: 0.875rem; color: #6b7280;">Progress</span>
                            <span style="font-size: 0.875rem; font-weight: 600;">{{ round($partnerProgress) }}%</span>
                        </div>
                        <div style="background: #e5e7eb; border-radius: 4px; height: 8px; overflow: hidden;">
                            <div style="background: #3b82f6; height: 100%; width: {{ $partnerProgress }}%; transition: width 0.3s ease;"></div>
                        </div>
                    </div>
                </div>

                <!-- Add Task Button -->
                <button onclick="showAddTaskModal()" style="width: 100%; background: #1e40af; color: white; padding: 12px; border: none; border-radius: 6px; cursor: pointer; font-weight: 600; display: flex; align-items: center; justify-content: center; gap: 8px;">
                    + Add Task
                </button>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div style="background: #f3f4f6; padding: 12px 16px; display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #e5e7eb;">
        <div style="font-size: 0.875rem; color: #6b7280;">
            Session started: Today at {{ now()->format('g:i A') }} • Duration: <span id="session-duration">0 minutes</span>
        </div>
        <button onclick="endSession()" style="background: #ef4444; color: white; padding: 8px 16px; border: none; border-radius: 4px; cursor: pointer; font-weight: 600;">End Session</button>
    </div>
</div>

<!-- Add Task Modal -->
<div id="add-task-modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; display: flex; align-items: center; justify-content: center;">
    <div style="background: white; padding: 24px; border-radius: 8px; width: 400px; max-width: 90%;">
        <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 16px;">Add Task for {{ $partner->firstname }}</h3>
        <form id="add-task-form">
            <div style="margin-bottom: 16px;">
                <label style="display: block; margin-bottom: 4px; font-weight: 500;">Task Title</label>
                <input type="text" id="task-title" required style="width: 100%; padding: 8px; border: 1px solid #d1d5db; border-radius: 4px;">
            </div>
            <div style="margin-bottom: 16px;">
                <label style="display: block; margin-bottom: 4px; font-weight: 500;">Description (Optional)</label>
                <textarea id="task-description" rows="3" style="width: 100%; padding: 8px; border: 1px solid #d1d5db; border-radius: 4px; resize: vertical;"></textarea>
            </div>
            <div style="display: flex; gap: 8px; justify-content: flex-end;">
                <button type="button" onclick="hideAddTaskModal()" style="padding: 8px 16px; border: 1px solid #d1d5db; background: white; border-radius: 4px; cursor: pointer;">Cancel</button>
                <button type="submit" style="padding: 8px 16px; background: #1e40af; color: white; border: none; border-radius: 4px; cursor: pointer;">Add Task</button>
            </div>
        </form>
    </div>
</div>

<!-- Hidden logout form -->
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>

<script>
// Laravel Echo is already initialized in bootstrap.js
// We'll use it to listen for events

// Listen for events using Laravel Echo
if (window.Echo) {
    // Listen for new messages
    window.Echo.channel('trade-{{ $trade->id }}')
        .listen('new-message', function(data) {
            console.log('Received new message event:', data);
            // Only add if it's not from the current user (to avoid duplicates)
            if (data.message.sender_id !== {{ Auth::id() }}) {
                addMessageToChat(data.message, data.sender_name, data.timestamp, false);
            }
        });

    // Listen for task updates
    window.Echo.channel('trade-{{ $trade->id }}')
        .listen('task-updated', function(data) {
            console.log('Received task update event:', data);
            updateTask(data.task);
            updateProgress();
        });
}

// Message handling
document.getElementById('message-form').addEventListener('submit', function(e) {
    e.preventDefault();
    const input = document.getElementById('message-input');
    const message = input.value.trim();
    
    if (message) {
        sendMessage(message);
        input.value = '';
    }
});

function sendMessage(message) {

    
    // Add message to UI immediately (optimistic update)
    const tempId = 'temp_' + Date.now();
    addMessageToChat(message, '{{ Auth::user()->firstname }} {{ Auth::user()->lastname }}', new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}), true, tempId);
    
    fetch('{{ route("chat.send-message", $trade->id) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ message: message })
    })
    .then(response => {

        return response.json();
    })
    .then(data => {

        if (data.success) {
            // Update the temporary message with the real one
            updateMessageInChat(tempId, data.message);
        } else {

            // Remove the temporary message if it failed
            removeMessageFromChat(tempId);
            showError('Failed to send message: ' + (data.error || 'Unknown error'));
        }
    })
    .catch(error => {

        // Remove the temporary message if it failed
        removeMessageFromChat(tempId);
                    showError('Failed to send message. Please try again.');
    });
}

function addMessageToChat(message, senderName, timestamp, isOwn, tempId = null) {
    const chatMessages = document.getElementById('chat-messages');
    const messageDiv = document.createElement('div');
    messageDiv.style.marginBottom = '16px';
    messageDiv.style.display = 'flex';
    messageDiv.style.justifyContent = isOwn ? 'flex-end' : 'flex-start';
    
    if (tempId) {
        messageDiv.setAttribute('data-temp-id', tempId);
    }
    
    // Handle both string messages and message objects
    const messageText = typeof message === 'string' ? message : message.message;
    const messageTime = timestamp || new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
    
    messageDiv.innerHTML = `
        <div style="max-width: 70%; ${isOwn ? 'background: #3b82f6; color: white;' : 'background: #e5e7eb; color: #374151;'} padding: 12px; border-radius: 12px; position: relative;">
            <div style="margin-bottom: 4px;">${messageText}</div>
            <div style="font-size: 0.75rem; opacity: 0.8;">${messageTime}</div>
        </div>
    `;
    
    chatMessages.appendChild(messageDiv);
    chatMessages.scrollTop = chatMessages.scrollHeight;
    
    // Flash effect for new messages (only for incoming messages, not your own)
    if (!isOwn) {
        console.log('🆕 New message added dynamically:', messageText);
        flashChatArea();
    }
    
    return messageDiv;
}

// Add flash effect function
function flashChatArea() {
    const chatMessages = document.getElementById('chat-messages');
    
    // Create flash overlay
    const flashOverlay = document.createElement('div');
    flashOverlay.style.cssText = `
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(45deg, rgba(59, 130, 246, 0.3), rgba(16, 185, 129, 0.3));
        border-radius: 8px;
        pointer-events: none;
        z-index: 10;
        opacity: 0;
        transition: opacity 0.3s ease;
    `;
    
    // Position the overlay relative to chat messages
    chatMessages.style.position = 'relative';
    chatMessages.appendChild(flashOverlay);
    
    // Trigger flash animation
    setTimeout(() => {
        flashOverlay.style.opacity = '1';
    }, 50);
    
    setTimeout(() => {
        flashOverlay.style.opacity = '0';
    }, 150);
    
    // Remove overlay after animation
    setTimeout(() => {
        if (flashOverlay.parentNode) {
            flashOverlay.parentNode.removeChild(flashOverlay);
        }
    }, 500);
    
    // Show new message indicator
    showNewMessageIndicator();
}

// Show new message indicator
function showNewMessageIndicator() {
    const indicator = document.getElementById('new-message-indicator');
    if (indicator) {
        indicator.style.display = 'inline-block';
        
        // Hide after 3 seconds
        setTimeout(() => {
            indicator.style.display = 'none';
        }, 3000);
    }
}

function updateMessageInChat(tempId, messageData) {
    const messageDiv = document.querySelector(`[data-temp-id="${tempId}"]`);
    if (messageDiv) {
        // Update with real message data
        messageDiv.removeAttribute('data-temp-id');
        messageDiv.setAttribute('data-message-id', messageData.id);
        
        const messageText = messageData.message;
        const messageTime = new Date(messageData.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
        
        messageDiv.innerHTML = `
            <div style="max-width: 70%; background: #3b82f6; color: white; padding: 12px; border-radius: 12px; position: relative;">
                <div style="margin-bottom: 4px;">${messageText}</div>
                <div style="font-size: 0.75rem; opacity: 0.8;">${messageTime}</div>
            </div>
        `;
    }
}

function removeMessageFromChat(tempId) {
    const messageDiv = document.querySelector(`[data-temp-id="${tempId}"]`);
    if (messageDiv) {
        messageDiv.remove();
    }
}

// Task handling
function toggleTask(taskId) {
    fetch(`/chat/task/${taskId}/toggle`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateTask(data.task);
            updateProgress();
        }
    })
    .catch(error => console.error('Error:', error));
}

function updateTask(task) {
    const taskElement = document.querySelector(`[data-task-id="${task.id}"]`);
    if (taskElement) {
        const checkbox = taskElement.querySelector('input[type="checkbox"]');
        const title = taskElement.querySelector('span');
        
        checkbox.checked = task.completed;
        if (task.completed) {
            title.style.textDecoration = 'line-through';
            title.style.color = '#6b7280';
        } else {
            title.style.textDecoration = 'none';
            title.style.color = '';
        }
    }
}

function updateProgress() {
    // Recalculate progress without reloading
    const myTasks = document.querySelectorAll('#my-tasks .task-item');
    const myCompletedTasks = document.querySelectorAll('#my-tasks .task-item input[type="checkbox"]:checked');
    const myProgress = myTasks.length > 0 ? (myCompletedTasks.length / myTasks.length) * 100 : 0;
    
    const partnerTasks = document.querySelectorAll('#partner-tasks .task-item');
    const partnerCompletedTasks = document.querySelectorAll('#partner-tasks .task-item input[type="checkbox"]:checked');
    const partnerProgress = partnerTasks.length > 0 ? (partnerCompletedTasks.length / partnerTasks.length) * 100 : 0;
    
    // Update progress bars
    const myProgressBar = document.querySelector('#my-tasks + div div[style*="background: #10b981"]');
    const partnerProgressBar = document.querySelector('#partner-tasks + div div[style*="background: #3b82f6"]');
    
    if (myProgressBar) {
        myProgressBar.style.width = myProgress + '%';
        myProgressBar.parentElement.previousElementSibling.querySelector('span:last-child').textContent = Math.round(myProgress) + '%';
    }
    
    if (partnerProgressBar) {
        partnerProgressBar.style.width = partnerProgress + '%';
        partnerProgressBar.parentElement.previousElementSibling.querySelector('span:last-child').textContent = Math.round(partnerProgress) + '%';
    }
}

// Modal handling
function showAddTaskModal() {
    document.getElementById('add-task-modal').style.display = 'flex';
}

function hideAddTaskModal() {
    document.getElementById('add-task-modal').style.display = 'none';
}

document.getElementById('add-task-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const title = document.getElementById('task-title').value;
    const description = document.getElementById('task-description').value;
    
    fetch('{{ route("chat.create-task", $trade->id) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            title: title,
            description: description,
            assigned_to: {{ $partner->id }}
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            hideAddTaskModal();
            addTaskToUI(data.task);
            // Clear form
            document.getElementById('task-title').value = '';
            document.getElementById('task-description').value = '';
        } else {
            showError('Failed to create task: ' + (data.error || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
                    showError('Failed to create task. Please try again.');
    });
});

function addTaskToUI(task) {
    const partnerTasksContainer = document.getElementById('partner-tasks');
    const taskDiv = document.createElement('div');
    taskDiv.className = 'task-item';
    taskDiv.setAttribute('data-task-id', task.id);
    taskDiv.style.cssText = 'margin-bottom: 12px; padding: 12px; background: #f9fafb; border-radius: 6px; border: 1px solid #e5e7eb;';
    
    taskDiv.innerHTML = `
        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
            <input type="checkbox" disabled style="width: 16px; height: 16px;">
            <span style="font-weight: 500;">${task.title}</span>
        </div>
        ${task.description ? `<div style="font-size: 0.875rem; color: #6b7280; margin-left: 24px;">${task.description}</div>` : ''}
    `;
    
    // Remove the "No tasks" message if it exists
    const noTasksMessage = partnerTasksContainer.querySelector('div[style*="text-align: center"]');
    if (noTasksMessage) {
        noTasksMessage.remove();
    }
    
    partnerTasksContainer.appendChild(taskDiv);
}

// Session duration timer
let sessionStart = new Date();
setInterval(function() {
    const now = new Date();
    const diff = Math.floor((now - sessionStart) / 60000);
    document.getElementById('session-duration').textContent = diff + ' minutes';
}, 60000);

// Keep track of the last message count
let lastMessageCount = {{ $messages->count() }};

// Check for new messages every 10 seconds (only if Laravel Echo is not working)
if (!window.Echo) {
    setInterval(checkForNewMessages, 1000);
}

function checkForNewMessages() {
    fetch('/chat/{{ $trade->id }}/messages')
        .then(response => response.json())
        .then(data => {
            if (data.success && data.count > lastMessageCount) {
                // Get only the new messages
                const newMessages = data.messages.slice(lastMessageCount);
                lastMessageCount = data.count;

                // Add only new messages to chat
                newMessages.forEach(msg => {
                    addMessageToChat(
                        msg,
                        msg.sender.firstname + ' ' + msg.sender.lastname,
                        new Date(msg.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }),
                        msg.sender_id === {{ Auth::id() }}
                    );
                });
            }
        })
        .catch(error => {
            console.error("Error checking for new messages:", error);
        });
}

function endSession() {
    if (confirm('Are you sure you want to end this session?')) {
        window.location.href = '{{ route("trades.ongoing") }}';
    }
}


</script>
@endsection
