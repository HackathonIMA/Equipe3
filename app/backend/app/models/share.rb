class Share < ActiveRecord::Base
  has_one :school
  enum category: {
    complain: 0,  # reclamação
    praise: 1,    # elogio
    notice: 2     # comunicado
  }
end
