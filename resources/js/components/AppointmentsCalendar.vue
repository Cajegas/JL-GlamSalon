<script setup>
import { ref, onMounted, computed } from 'vue'
import FullCalendar from '@fullcalendar/vue3'
import dayGridPlugin from '@fullcalendar/daygrid'
import timeGridPlugin from '@fullcalendar/timegrid'
import interactionPlugin from '@fullcalendar/interaction'

const props = defineProps({
  appointments: Array
})

// Convert Laravel appointments into FullCalendar format
const events = ref(
  props.appointments.map(app => ({
    id: app.id,
    title: app.title,
    start: app.start,
    end: app.end,
     extendedProps: {
      services: app.services,
      status: app.status,
      color: app.color 
    },
    color: app.color,  
    textColor: '#ffffff'
  }))
)

const emit = defineEmits(['appointment-selected'])
const isMobile = ref(window.matchMedia('(max-width: 768px)').matches)

onMounted(() => {
  const mediaQuery = window.matchMedia('(max-width: 768px)')
  mediaQuery.addEventListener('change', e => {
    isMobile.value = e.matches
  })
})

const calendarOptions = computed(() => ({
  plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin],
  initialView: 'timeGridWeek',
  height: isMobile.value ? 'auto' : 'auto',

  headerToolbar: isMobile.value
  ?{
    left: 'prev,next',
    center: 'title',
    right: 'today'
  }
  :{
    left: 'prev,next today',
    center: 'title',
    right: 'dayGridMonth,timeGridWeek,timeGridDay'
  },
   eventTimeFormat: {
    hour: 'numeric',
    minute: '2-digit',
    meridiem: 'short'
  },
  events: events.value,
  slotMinTime: '05:00:00',
  slotMaxTime: '20:00:00',

  eventContent: function(arg) {

    if (arg.view.type === 'dayGridMonth') {
        return true; 
    }
    const timeText = arg.timeText;
    const name = arg.event.title; 
    const services = arg.event.extendedProps.services;
    
    return {
      html: `
        <div>
            <div style="font-weight:600; font-size:12px;">${timeText}</div>
            <div style="font-weight:600; font-size:13px;">${name} - <span style="font-size:12px">${services}</span> </div>

        </div>
        `
    };

  },

  eventClick: (arg) => {
    emit('appointment-selected', arg.event)
  }
}))
</script>

<style>
    .fc .fc-daygrid-day {
        height: 80px;
        min-height: 80px;
    }

    .fc .fc-event {
        padding: 2px 4px;
        font-size: 0.75rem;
        transition: transform 0.2s, box-shadow 0.2s;
        border-radius: 6px;
    }

    .fc .fc-event:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        cursor: pointer;
        z-index: 10;
    }

    @media (max-width: 768px) {

      .fc .fc-toolbar-title {
        font-size: 1rem;  
        font-weight: 600;
      }

      .fc .fc-button {
        padding: 5px 7px;
        font-size: 0.7rem;  
      }

      .fc .fc-button .fc-icon {
        font-size: 0.8rem;   
      }

    }
</style>

<template>
  <FullCalendar :options="calendarOptions" />
</template>
