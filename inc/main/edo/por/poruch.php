<style>
.important-event {
  font-weight: bold;
  color: red !important;
  background-color: white !important;
  border-left: 5px solid red !important;
}
.add-event {
  font-weight: bold;
  color: gray !important;
  background-color: white !important;
  border-left: 5px solid gray !important;
}
.fc-event::before {
  display: none !important;
}
.fc-daygrid-event-dot {
  display: none !important;
}
.fc-toolbar-title {
  font-size: 14px !important;
  /*font-weight: bold;*/ /* Сделать жирным */
}
</style>
<div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <div id='calendar'></div>
                            </div>
                        </div>
                    </div>


                                            <!-- Modal -->
                                            <div class="modal fade" id="exampleFullScreenModal" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-fullscreen">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Окно события</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body" id="bnbnbn"></div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть окно</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


<script>
    //document.addEventListener('DOMContentLoaded', function () {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            headerToolbar: {
                //left: 'prev,next today',
                //center: 'title',
                //right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
            },
            initialView: 'listMonth',
            contentHeight: 520,
            initialDate: '<?=date('Y-m-d')?>',
            navLinks: true, // can click day/week names to navigate views
            selectable: true,
            nowIndicator: true,
            dayMaxEvents: true, // allow "more" link when too many events
            editable: true,
            selectable: true,
            businessHours: true,
            dayMaxEvents: true, // allow "more" link when too many events
            themeSystem: "bootstrap5",
            locale: 'ru',
            //refetchResourcesOnNavigate: true,
            //resources: 'modules.php?pa=plan-json-calendar',
            events: 'modules.php?pa=edo-json-por&div=1',
            eventContent: function(arg) {
              var description = arg.event.extendedProps.description ? `<br><small>${arg.event.extendedProps.description}</small>` : '';
              //let location = arg.event.extendedProps.location ? `<br><i>${arg.event.extendedProps.location}</i>` : '';
              if (arg.event.extendedProps.type=='add') {
                return { html: `${arg.event.title}` };
              } else {
                return { html: `<b>${arg.timeText}</b> ${arg.event.title}${description}` };
              }

            },
            eventClick: function(info, jsEvent, view) {
                console.log(info.event);
                $('#bnbnbn').text('');
                if (info.event.extendedProps.type=='edoeventsauthor') {
                  $('#bnbnbn').load('modules.php?pa=edo-newpor-por&events_id='+info.event.id);
                } else if (info.event.extendedProps.type=='plandocs') {
                  $('#bnbnbn').load('modules.php?pa=edo-modal-por&events_id='+info.event.id);
                } else if (info.event.extendedProps.type=='edoevents') {
                  $('#bnbnbn').load('modules.php?pa=edo-modal-por&events_id='+info.event.id);
                } else if (info.event.extendedProps.type=='add') {
                  $.post( 'modules.php?pa=edo-myporuchdet-por&category_id=3&date='+info.event.extendedProps.description, { name: "John", time: "2pm" })
                        .done(function( data ) {
                          $('#bnbnbn').html(data)
                        });
                  //$('#bnbnbn').load('modules.php?pa=edo-myporuchdet-por&category_id=3&date='+info.event.extendedProps.description);
                }

                //$('#exampleFullScreenModal').modal('show');
            },
        });
        calendar.render();
    //});
</script>
