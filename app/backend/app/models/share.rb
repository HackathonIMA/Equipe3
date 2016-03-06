class Share < ActiveRecord::Base
  belongs_to :school
  belongs_to :user

  enum category: {
    complain: 0,  # reclamação
    praise: 1,    # elogio
    notice: 2     # comunicado
  }
end
