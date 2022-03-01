/**
 * Get sidebar parts
 * @returns Sidebar Parts
 */
function getSidebarParts() {
  let sidebar = document.querySelector('.sidebar'),
  sidebarParts = sidebar.querySelectorAll('.sidebar-part')

  return sidebarParts
}

/**
 * Attach to sidebar parts to targeted sections 
 */
function attachSidebarParts() {
  let sidebarParts = getSidebarParts()
  
  for (let i = 0; i < sidebarParts.length; i++) {
    let target = sidebarParts[i].getAttribute('data-target')
    if (target) {
      let targetOffset = document.getElementById(target).offsetTop,
          elOffset = sidebarParts[i].offsetTop
          
      sidebarParts[i].style.top = targetOffset - elOffset + 'px'
    }
  }
}

document.addEventListener('DOMContentLoaded', function () {
  if (window.innerWidth > 992)  {
    attachSidebarParts()
  }
})