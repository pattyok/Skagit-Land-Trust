import "./style.scss";
const { render } = wp.element;
import Popup from "reactjs-popup";

const popupGroups = document.querySelectorAll(".wp-block-carkeek-blocks-team-members");

popupGroups.forEach(popupGroup => {
    const layout = popupGroup.getAttribute('data-layout');
    const popups = popupGroup.querySelectorAll(".wp-block-carkeek-blocks-team-member");
    popups.forEach(popup => {
        const popupContent = popup.innerHTML;
        let triggerContent, name, title;
        if (layout == "grid") {
            triggerContent = popup.querySelectorAll(".wp-block-carkeek-blocks-team-member__initial");
            triggerContent = {__html: triggerContent[0].innerHTML};
        } else {
            name = popup.querySelectorAll(".wp-block-carkeek-blocks-team-member__name");
            name = name[0].outerText;
            title = popup.querySelectorAll(".wp-block-carkeek-blocks-team-member__title");
            if (title.length > 0) {
                name += ', ' + title[0].outerText
            }
            triggerContent = {__html: '<div class="wp-block-carkeek-blocks-team-member__name">' + name + '</div>'}
        }

        render(
            <Popup
            trigger={ <div dangerouslySetInnerHTML={triggerContent}/> }
            modal
            closeOnDocumentClick
            >
            {close => (
                <div className="modal">
                    <div className="modal-header"><a className="close" onClick={close}>
                        &times;
                    </a>
                    </div>
                    <div className="content" dangerouslySetInnerHTML={{__html: popupContent}}/>
                </div>
            )}
            </Popup>, popup
        )
    });
});
